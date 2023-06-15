<?php

namespace App\Http\Controllers\Backend;

use App\Exports\DokterExport;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Dokter;
use App\Models\Jabatan;
use App\Models\Province;
use App\Models\Regency;
use App\Models\User;
use App\Models\Village;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class DokterController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $dokter = Dokter::with('user', 'jabatan')->get();
            return DataTables::of($dokter)
                ->addIndexColumn()
                ->addColumn('comboBox', function ($data) {
                    $comboBox = "<input type='checkbox' class='checkbox' data-id='" . $data->user->id . "'>";
                    return $comboBox;
                })
                ->addColumn('name', function ($data) {
                    $name = $data->user->name;
                    return $name;
                })
                ->addColumn('email', function ($data) {
                    $email = $data->user->email;
                    return $email;
                })
                ->addColumn('no_telepon', function ($data) {
                    $no_telepon = $data->user->no_telepon;
                    return $no_telepon;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == '0') {
                        $badgeStatus = '<span class="badge bg-success">Melayanani</span>';
                        return $badgeStatus;
                    } else {
                        $badgeStatus = '<span class="badge bg-danger">Tidak Sedang Melayanani</span>';
                        return $badgeStatus;
                    }
                })
                ->addColumn('aksi', function ($data) {
                    $btn = '<button type="button" class="btn btn-info btn-sm me-1" id="btn-detail" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fa-solid fa-circle-info"></i></button>';
                    $btn = $btn . '<a class="btn btn-warning btn-sm me-1" href="' . route('dokter.edit', $data->id) . '" ><i
                    class="mdi mdi-pencil"></i></a>';
                    $btn = $btn . '<button type="button" class="btn btn-danger btn-sm" data-id="' . $data->user->id . '" id="btnHapus"><i
                    class="mdi mdi-trash-can"></i></button>';
                    return $btn;
                })
                ->rawColumns(['aksi', 'comboBox', 'status'])
                ->make(true);
        }
        return view('backend.dokter.index');
    }

    public function detail(Request $request)
    {
        $dokter = Dokter::with('user', 'jabatan')->findOrFail($request->id);
        return response()->json(['dokter' => $dokter]);
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
        $jabatan = Jabatan::orderBy('name', 'asc')->get();
        $provinces = Province::all();
        return view('backend.dokter.add', compact('jabatan', 'provinces'));
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'nip' => 'required|string|unique:dokter,nip',
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'no_telepon' => 'required|unique:users,no_telepon|min:11|max:15',
                'jabatan' => 'required|string',
                'spesialis' => 'required|string',
                'pengalaman' => 'required|string',
                'jam_masuk' => 'required|string',
                'jam_pulang' => 'required|string',
                'deskripsi' => 'required|string',
                'hari' => 'required|array',
            ],
            [
                'nip.required' => 'Silakan isi nip terlebih dahulu!',
                'nip.unique' => 'NIP sudah digunakan!',
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'email.required' => 'Silakan isi email terlebih dahulu!',
                'email.unique' => 'Email sudah digunakan!',
                'no_telepon.unique' => 'No telepon sudah digunakan!',
                'no_telepon.required' => 'Silakan isi no telepon terlebih dahulu!',
                'no_telepon.min' => 'No telepon harus memiliki panjang minimal 11 karakter.',
                'no_telepon.max' => 'No telepon harus memiliki panjang maksimal 15 karakter.',
                'jabatan.required' => 'Silakan isi jabatan terlebih dahulu!',
                'spesialis.required' => 'Silakan isi spesialis terlebih dahulu!',
                'pengalaman.required' => 'Silakan isi pengalaman terlebih dahulu!',
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
            $user->type = 3;
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->provinsi_id = $request->provinsi;
            $user->kabupaten_id = $request->kabupaten;
            $user->kecamatan_id = $request->kecamatan;
            $user->desa_id = $request->desa;
            $user->save();

            $dokter = new Dokter();
            $dokter->user_id = $user->id;
            $dokter->nip = $request->nip;
            $dokter->gol_darah = $request->gol_darah;
            $dokter->tempat_lahir = $request->tempat_lahir;
            $dokter->tanggal_lahir = $request->tanggal_lahir;
            $dokter->agama = $request->agama;
            $dokter->status_nikah = $request->status_nikah;
            $dokter->jabatan_id = $request->jabatan;
            $dokter->spesialis = $request->spesialis;
            $dokter->pengalaman_tahun = $request->pengalaman;
            $dokter->deskripsi = $request->deskripsi;
            $dokter->jam_masuk = $request->jam_masuk;
            $dokter->jam_pulang = $request->jam_pulang;
            $dokter->hari = implode(',', $request->hari);
            $dokter->save();

            return response()->json(['success' => 'Data barhasil ditambahkan']);
        }
    }


    public function edit($id)
    {
        $dokter = Dokter::with('user', 'jabatan')->findOrFail($id);
        $hari = explode(",", $dokter->hari);
        $jabatan = Jabatan::orderBy('name', 'asc')->get();
        $provinces = Province::all();
        return view('backend.dokter.edit', compact('jabatan', 'dokter', 'hari', 'provinces'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $user_id = $request->user_id;
        $validated = Validator::make(
            $request->all(),
            [
                'nip' => 'required|string|unique:dokter,nip,' . $id . ',id',
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $user_id . ',id',
                'no_telepon' => 'required|unique:users,no_telepon,' . $user_id . ',id|min:11|max:15',
                'jabatan' => 'required|string',
                'spesialis' => 'required|string',
                'pengalaman' => 'required|string',
                'jam_masuk' => 'required|string',
                'jam_pulang' => 'required|string',
                'deskripsi' => 'required|string',
                'hari' => 'required|array',
                'hari.required' => 'Silakan isi hari terlebih dahulu!',
            ],
            [
                'nip.required' => 'Silakan isi nip terlebih dahulu!',
                'nip.unique' => 'NIP sudah digunakan!',
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'email.required' => 'Silakan isi email terlebih dahulu!',
                'email.unique' => 'Email sudah digunakan!',
                'no_telepon.unique' => 'No telepon sudah digunakan!',
                'no_telepon.required' => 'Silakan isi no telepon terlebih dahulu!',
                'no_telepon.min' => 'No telepon harus memiliki panjang minimal 11 karakter.',
                'no_telepon.max' => 'No telepon harus memiliki panjang maksimal 15 karakter.',
                'jabatan.required' => 'Silakan isi jabatan terlebih dahulu!',
                'spesialis.required' => 'Silakan isi spesialis terlebih dahulu!',
                'pengalaman.required' => 'Silakan isi pengalaman terlebih dahulu!',
                'jam_masuk.required' => 'Silakan isi jam masuk terlebih dahulu!',
                'jam_pulang.required' => 'Silakan isi jam pulang terlebih dahulu!',
                'deskripsi.required' => 'Silakan isi deskripsi terlebih dahulu!',
            ]
        );


        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $user = User::find($user_id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_telepon = $request->no_telepon;
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->provinsi_id = $request->provinsi;
            $user->kabupaten_id = $request->kabupaten;
            $user->kecamatan_id = $request->kecamatan;
            $user->desa_id = $request->desa;
            $user->save();

            $dokter = Dokter::find($id);
            $dokter->nip = $request->nip;
            $dokter->gol_darah = $request->gol_darah;
            $dokter->tempat_lahir = $request->tempat_lahir;
            $dokter->tanggal_lahir = $request->tanggal_lahir;
            $dokter->agama = $request->agama;
            $dokter->status_nikah = $request->status_nikah;
            $dokter->jabatan_id = $request->jabatan;
            $dokter->spesialis = $request->spesialis;
            $dokter->pengalaman_tahun = $request->pengalaman;
            $dokter->deskripsi = $request->deskripsi;
            $dokter->jam_masuk = $request->jam_masuk;
            $dokter->jam_pulang = $request->jam_pulang;
            $dokter->hari = implode(',', $request->hari);
            $dokter->save();

            return response()->json(['success' => 'Data barhasil ditambahkan']);
        }
    }

    public function destroy(Request $request)
    {
        $dokter = User::findOrFail($request->id);
        $dokter->delete();
        return Response()->json(['dokter' => $dokter, 'success' => 'Data berhasil dihapus']);
    }


    public function deleteMultiple(Request $request)
    {
        $id = $request->id;
        User::whereIn('id', explode(",", $id))->delete();
        return response()->json(['success' => "Data berhasil dihapus"]);
    }

    public function printPDF()
    {
        $dokter = Dokter::with('user', 'jabatan')->get();

        $pdf = Pdf::loadView('backend.dokter.printPDF', compact('dokter'));
        return $pdf->download('data-dokter-' . time() . '.pdf');
    }

    public function exportExcel()
    {
        $dokter = Dokter::with('user', 'jabatan')->get();
        return Excel::download(new DokterExport($dokter), 'data-dokter.xlsx');
    }
}
