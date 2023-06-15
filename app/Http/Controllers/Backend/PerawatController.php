<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PerawatExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Jabatan;
use App\Models\Perawat;
use App\Models\Province;
use App\Models\Regency;
use App\Models\User;
use App\Models\Village;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PerawatController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $perawat =  Perawat::with('jabatan', 'user')->get();
            return DataTables::of($perawat)
                ->addIndexColumn()
                ->addColumn('comboBox', function ($data) {
                    $comboBox = "<input type='checkbox' class='checkbox' data-id='" . $data->user->id . "'>";
                    return $comboBox;
                })
                ->addColumn('name', function ($data) {
                    $user = $data->user->name;
                    return $user;
                })
                ->addColumn('no_telepon', function ($data) {
                    $user = $data->user->no_telepon;
                    return $user;
                })
                ->addColumn('jabatan', function ($data) {
                    $jabatan = $data->jabatan->name;
                    return $jabatan;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == '0') {
                        $badgeStatus = '<span class="badge bg-success">Melayani</span>';
                        return $badgeStatus;
                    } else {
                        $badgeStatus = '<span class="badge bg-danger">Tidak Sedang Melayani</span>';
                        return $badgeStatus;
                    }
                })
                ->addColumn('aksi', function ($data) {
                    $btn = '<button type="button" class="btn btn-info btn-sm me-1" id="btn-detail" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fa-solid fa-circle-info"></i></button>';
                    $btn = $btn . '<a class="btn btn-warning btn-sm me-1" href="' . route('perawat.edit', $data->user->id) . '" ><i
                    class="mdi mdi-pencil"></i></a>';
                    $btn = $btn . '<button type="button" class="btn btn-danger btn-sm" data-id="' . $data->user->id . '" id="btnHapus"><i
                    class="mdi mdi-trash-can"></i></button>';
                    return $btn;
                })
                ->rawColumns(['aksi', 'comboBox', 'status'])
                ->make(true);
        }
        return view('backend.perawat.index');
    }

    public function detail(Request $request)
    {
        $perawat =  Perawat::with('jabatan', 'user')->findOrFail($request->id);
        return response()->json(['perawat' => $perawat]);
    }

    public function getKabupaten(Request $request)
    {
        $id_provinsi = $request->id_provinsi;
        $kabupaten = Regency::where('province_id', $id_provinsi)->get();
        foreach ($kabupaten as $row) {
            echo "<option value='" . $row->id . "'>" . $row->name . "</option>";
        }
    }

    public function getKecamatan(Request $request)
    {
        $id_kabupaten = $request->id_kabupaten;
        $kecamatan = District::where('regency_id', $id_kabupaten)->get();
        foreach ($kecamatan as $row) {
            echo "<option value='" . $row->id . "'>" . $row->name . "</option>";
        }
    }

    public function getDesa(Request $request)
    {
        $id_kecamatan = $request->id_kecamatan;
        $desa = Village::where('district_id', $id_kecamatan)->get();
        foreach ($desa as $row) {
            echo "<option value='" . $row->id . "'>" . $row->name . "</option>";
        }
    }


    public function create()
    {
        $provinces = Province::all();
        $jabatan = Jabatan::orderBy('name', 'asc')->get();
        return view('backend.perawat.add', compact('jabatan', 'provinces'));
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'nip' => 'required|string|unique:perawat,nip',
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'no_telepon' => 'required|unique:users,no_telepon|min:11|max:15',
                'jabatan' => 'required|string',
                'jam_masuk' => 'required|string',
                'jam_pulang' => 'required|string',
                'deskripsi' => 'required|string',
                'hari' => 'required|array',
            ],
            [
                'nip.required' => 'Silakan isi nip terlebih dahulu!',
                'jabatan.required' => 'Silakan isi jabatan terlebih dahulu!',
                'nip.unique' => 'NIP sudah digunakan!',
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'email.required' => 'Silakan isi email terlebih dahulu!',
                'email.unique' => 'Email sudah digunakan!',
                'no_telepon.required' => 'Silakan isi no telepon terlebih dahulu!',
                'no_telepon.unique' => 'No telepon sudah digunakan!',
                'no_telepon.min' => 'No telepon harus memiliki panjang minimal 11 karakter.',
                'no_telepon.max' => 'No telepon harus memiliki panjang maksimal 15 karakter.',
                'jam_masuk.required' => 'Silakan isi jam masuk terlebih dahulu!',
                'jam_pulang.required' => 'Silakan isi jam pulang terlebih dahulu!',
                'deskripsi.required' => 'Silakan isi deskripsi terlebih dahulu!',
                'hari.required' => 'Silakan isi hari terlebih dahulu!',
            ]
        );


        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_telepon = $request->no_telepon;
            $user->password = bcrypt($request->no_telepon);
            $user->type = 2;
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->provinsi_id = $request->provinsi;
            $user->kabupaten_id = $request->kabupaten;
            $user->kecamatan_id = $request->kecamatan;
            $user->desa_id = $request->desa;
            $user->save();

            $perawat = new Perawat();
            $perawat->user_id = $user->id;
            $perawat->nip = $request->nip;
            $perawat->gol_darah = $request->gol_darah;
            $perawat->tempat_lahir = $request->tempat_lahir;
            $perawat->tanggal_lahir = $request->tanggal_lahir;
            $perawat->agama = $request->agama;
            $perawat->status_nikah = $request->status_nikah;
            $perawat->jabatan_id = $request->jabatan;
            $perawat->jam_masuk = $request->jam_masuk;
            $perawat->jam_pulang = $request->jam_pulang;
            $perawat->hari = implode(',', $request->hari);
            $perawat->deskripsi = $request->deskripsi;
            $perawat->save();

            return response()->json(['success' => 'Data barhasil ditambahkan']);
        }
    }

    public function edit($id)
    {
        $perawat = User::with('perawat')->findOrFail($id);
        $hari = explode(",", $perawat->perawat->hari);
        $jabatan = Jabatan::orderBy('name', 'asc')->get();
        $provinces = Province::all();
        return view('backend.perawat.edit', compact(['perawat', 'jabatan', 'hari', 'provinces']));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $id_perawat = $request->id_perawat;
        $validated = Validator::make(
            $request->all(),
            [
                'nip' => 'required|string|unique:perawat,nip,' . $id_perawat . ',id',
                'name' => 'required|string',
                'jabatan' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $id . ',id',
                'no_telepon' => 'required|unique:users,no_telepon,' . $id . ',id|min:11|max:15',
                'jam_masuk' => 'required|string',
                'jam_pulang' => 'required|string',
                'deskripsi' => 'required|string',
                'hari' => 'required|array',
            ],
            [
                'nip.required' => 'Silakan isi nip terlebih dahulu!',
                'jabatan.required' => 'Silakan isi jabatan terlebih dahulu!',
                'nip.unique' => 'NIP sudah digunakan!',
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'email.required' => 'Silakan isi email terlebih dahulu!',
                'email.unique' => 'Email sudah digunakan!',
                'no_telepon.required' => 'Silakan isi no telepon terlebih dahulu!',
                'no_telepon.unique' => 'No telepon sudah digunakan!',
                'no_telepon.min' => 'No telepon harus memiliki panjang minimal 11 karakter.',
                'no_telepon.max' => 'No telepon harus memiliki panjang maksimal 15 karakter.',
                'jam_masuk.required' => 'Silakan isi jam masuk terlebih dahulu!',
                'jam_pulang.required' => 'Silakan isi jam pulang terlebih dahulu!',
                'deskripsi.required' => 'Silakan isi deskripsi terlebih dahulu!',
                'hari.required' => 'Silakan isi hari terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $user = User::find($id);
            $perawat = $user->perawat;

            // Mengupdate data pada tabel users
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,
                'gender' => $request->gender,
                'address' => $request->address,
                'provinsi_id' => $request->provinsi,
                'kabupaten_id' => $request->kabupaten,
                'kecamatan_id' => $request->kecamatan,
                'desa_id' => $request->desa,
            ]);

            // Mengupdate data pada tabel perawat
            $perawat->update([
                'nip' => $request->nip,
                'gol_darah' => $request->gol_darah,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => $request->agama,
                'status_nikah' => $request->status_nikah,
                'jabatan_id' => $request->jabatan,
                'jam_masuk' => $request->jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'hari' => implode(',', $request->hari),
                'deskripsi' => $request->deskripsi,
            ]);
            return response()->json(['success' => 'Data barhasil diedit']);
        }
    }

    public function destroy(Request $request)
    {
        $perawat = User::findOrFail($request->id);

        if ($perawat->avatar !== 'avatar.png') {
            Storage::delete('users-avatar/' . $perawat->avatar);
            $perawat->delete();
        } else {
            $perawat->delete();
        }

        return Response()->json(['perawat' => $perawat, 'success' => 'Data berhasil dihapus']);
    }

    public function deleteMultiple(Request $request)
    {
        $perawat = User::whereIn('id', explode(",", $request->id))->get();

        foreach ($perawat as $row) {
            if ($row->avatar !== 'avatar.png') {
                Storage::delete('users-avatar/' . $row->avatar);
                $row->delete();
            } else {
                $row->delete();
            }
        }

        return response()->json(['success' => "Data berhasil dihapus"]);
    }

    public function printPDF()
    {
        $perawat =  Perawat::with('jabatan', 'user')->get();

        $pdf = Pdf::loadView('backend.perawat.printPDF', compact('perawat'));
        return $pdf->download('data-perawat-' . time() . '.pdf');
    }

    public function exportExcel()
    {
        $perawat =  Perawat::with('jabatan', 'user')->get();
        return Excel::download(new PerawatExport($perawat), 'data-perawat.xlsx');
    }
}
