<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PasienExport;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Pasien;
use App\Models\Province;
use App\Models\Regency;
use App\Models\User;
use App\Models\Village;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class PasienController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $pasien = User::where('type', 0)->orderBy('name', 'asc')->get(['id', 'name', 'email', 'no_telepon', 'address']);
            return DataTables::of($pasien)
                ->addIndexColumn()
                ->addColumn('comboBox', function ($data) {
                    $comboBox = "<input type='checkbox' class='checkbox' data-id='" . $data->id . "'>";
                    return $comboBox;
                })
                ->addColumn('aksi', function ($data) {
                    $btn = '<button type="button" class="btn btn-info btn-sm me-1" id="btn-detail" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fa-solid fa-circle-info"></i></button>';
                    if (auth()->user()->type == 'Administrator') {
                        $btn = $btn . '<a class="btn btn-warning btn-sm me-1" href="' . route('pasien.edit', $data->id) . '" ><i
                    class="mdi mdi-pencil"></i></a>';
                        $btn = $btn . '<button type="button" class="btn btn-danger btn-sm" data-id="' . $data->id . '" id="btnHapus"><i
                    class="mdi mdi-trash-can"></i></button>';
                    }
                    return $btn;
                })
                ->rawColumns(['aksi', 'comboBox'])
                ->make(true);
        }
        return view('backend.pasien.index');
    }

    public function detail(Request $request)
    {
        $pasien = User::with('pasien')->find($request->id);
        return response()->json(['pasien' => $pasien]);
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
        return view('backend.pasien.add', compact('provinces'));
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'no_telepon' => 'required|unique:users,no_telepon|min:11|max:15',
                'ktp' => 'required|image|mimes:jpg,png,jpeg,webp,svg',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'email.required' => 'Silakan isi email terlebih dahulu!',
                'email.unique' => 'Email sudah digunakan!',
                'no_telepon.required' => 'Silakan isi no telepon terlebih dahulu!',
                'no_telepon.unique' => 'No telepon sudah digunakan!',
                'no_telepon.min' => 'No telepon harus memiliki panjang minimal 11 karakter.',
                'no_telepon.max' => 'No telepon harus memiliki panjang maksimal 15 karakter.',
                'ktp.required' => 'Silakan isi KTP terlebih dahulu!',
                'ktp.image' => 'File harus berupa gambar!',
                'ktp.mimes' => 'Pilihan gambar yang diunggah harus dalam format JPG, PNG, JPEG, WEBP, atau SVG.',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            if ($request->hasFile('ktp')) {
                $file = $request->file('ktp');
                if ($file->isValid()) {
                    $guessExtension = $request->file('ktp')->guessExtension();
                    $request->file('ktp')->storeAs('ktp/', 'KTP - ' . $request->name . '.' . $guessExtension, 'public');

                    $user = new User();
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->no_telepon = $request->no_telepon;
                    $user->password = bcrypt($request->no_telepon);
                    $user->type = 0;
                    $user->gender = $request->gender;
                    $user->address = $request->address;
                    $user->provinsi_id = $request->provinsi;
                    $user->kabupaten_id = $request->kabupaten;
                    $user->kecamatan_id = $request->kecamatan;
                    $user->desa_id = $request->desa;
                    $user->save();

                    $pasien = new Pasien();
                    $pasien->user_id = $user->id;
                    $pasien->gol_darah = $request->gol_darah;
                    $pasien->tempat_lahir = $request->tempat_lahir;
                    $pasien->tanggal_lahir = $request->tanggal_lahir;
                    $pasien->agama = $request->agama;
                    $pasien->status_nikah = $request->status_nikah;
                    $pasien->pekerjaan = $request->pekerjaan;
                    $pasien->ktp = 'KTP - ' . $request->name . '.' . $guessExtension;
                    $pasien->save();

                    return response()->json(['success' => 'Data barhasil ditambahkan']);
                }
            } else {
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->no_telepon = $request->no_telepon;
                $user->password = bcrypt($request->no_telepon);
                $user->type = 0;
                $user->gender = $request->gender;
                $user->address = $request->address;
                $user->provinsi_id = $request->provinsi;
                $user->kabupaten_id = $request->kabupaten;
                $user->kecamatan_id = $request->kecamatan;
                $user->desa_id = $request->desa;
                $user->save();

                $pasien = new Pasien();
                $pasien->user_id = $user->id;
                $pasien->gol_darah = $request->gol_darah;
                $pasien->tempat_lahir = $request->tempat_lahir;
                $pasien->tanggal_lahir = $request->tanggal_lahir;
                $pasien->agama = $request->agama;
                $pasien->status_nikah = $request->status_nikah;
                $pasien->pekerjaan = $request->pekerjaan;
                $pasien->save();

                return response()->json(['success' => 'Data barhasil ditambahkan']);
            }
        }
    }

    public function edit($id)
    {
        $pasien = User::with('pasien')->find($id);
        $provinces = Province::all();
        return view('backend.pasien.edit', compact('pasien', 'provinces'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $pasien_id = $request->pasien_id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $id . ',id',
                'no_telepon' => 'required|unique:users,no_telepon,' . $id . ',id|min:11|max:15',
                'ktp' => 'image|mimes:jpg,png,jpeg,webp,svg',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'email.required' => 'Silakan isi email terlebih dahulu!',
                'email.unique' => 'Email sudah digunakan!',
                'no_telepon.required' => 'Silakan isi no telepon terlebih dahulu!',
                'no_telepon.unique' => 'No telepon sudah digunakan!',
                'no_telepon.min' => 'No telepon harus memiliki panjang minimal 11 karakter.',
                'no_telepon.max' => 'No telepon harus memiliki panjang maksimal 15 karakter.',
                'ktp.image' => 'File harus berupa gambar!',
                'ktp.mimes' => 'Pilihan gambar yang diunggah harus dalam format JPG, PNG, JPEG, WEBP, atau SVG.',
            ]
        );


        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            if ($request->hasFile('ktp')) {
                $file = $request->file('ktp');
                if ($file->isValid()) {
                    $pasien = User::with('pasien')->find($id);
                    if ($pasien->pasien->ktp !== '') {
                        Storage::delete('ktp/' . $pasien->pasien->ktp);
                    }
                    $guessExtension = $request->file('ktp')->guessExtension();
                    $request->file('ktp')->storeAs('ktp/', 'KTP - ' . $request->name . '.' . $guessExtension, 'public');

                    $user = User::find($id);
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->no_telepon = $request->no_telepon;
                    $user->password = bcrypt($request->no_telepon);
                    $user->type = 0;
                    $user->gender = $request->gender;
                    $user->address = $request->address;
                    $user->provinsi_id = $request->provinsi;
                    $user->kabupaten_id = $request->kabupaten;
                    $user->kecamatan_id = $request->kecamatan;
                    $user->desa_id = $request->desa;
                    $user->save();

                    $pasien = Pasien::find($pasien_id);
                    $pasien->user_id = $user->id;
                    $pasien->gol_darah = $request->gol_darah;
                    $pasien->tempat_lahir = $request->tempat_lahir;
                    $pasien->tanggal_lahir = $request->tanggal_lahir;
                    $pasien->agama = $request->agama;
                    $pasien->status_nikah = $request->status_nikah;
                    $pasien->pekerjaan = $request->pekerjaan;
                    $pasien->ktp = 'KTP - ' . $request->name . '.' . $guessExtension;
                    $pasien->save();

                    return response()->json(['success' => 'Data barhasil disimpan']);
                }
            } else {
                $user = User::find($id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->no_telepon = $request->no_telepon;
                $user->password = bcrypt($request->no_telepon);
                $user->type = 0;
                $user->gender = $request->gender;
                $user->address = $request->address;
                $user->provinsi_id = $request->provinsi;
                $user->kabupaten_id = $request->kabupaten;
                $user->kecamatan_id = $request->kecamatan;
                $user->desa_id = $request->desa;
                $user->save();

                $pasien = Pasien::find($pasien_id);
                $pasien->user_id = $user->id;
                $pasien->gol_darah = $request->gol_darah;
                $pasien->tempat_lahir = $request->tempat_lahir;
                $pasien->tanggal_lahir = $request->tanggal_lahir;
                $pasien->agama = $request->agama;
                $pasien->status_nikah = $request->status_nikah;
                $pasien->pekerjaan = $request->pekerjaan;
                $pasien->save();

                return response()->json(['success' => 'Data barhasil disimpan']);
            }
        }
    }

    public function destroy(Request $request)
    {
        $pasien = User::findOrFail($request->id);

        if ($pasien->pasien->ktp !== '') {
            Storage::delete('ktp/' . $pasien->pasien->ktp);
            $pasien->delete();
        } else {
            $pasien->delete();
        }

        return Response()->json(['pasien' => $pasien, 'success' => 'Data berhasil dihapus']);
    }

    public function deleteMultiple(Request $request)
    {
        $pasien = User::with('pasien')->whereIn('id', explode(",", $request->id))->get();

        foreach ($pasien as $row) {
            if ($row->pasien->ktp !== '') {
                Storage::delete('ktp/' . $row->pasien->ktp);
                $row->delete();
            } else {
                $row->delete();
            }
        }

        return response()->json(['success' => "Data berhasil dihapus"]);
    }

    public function printPDF()
    {
        $pasien = User::where('type', 0)->orderBy('name', 'asc')->get();

        $pdf = Pdf::loadView('backend.pasien.printPDF', compact('pasien'));
        return $pdf->download('data-pasien-' . time() . '.pdf');
    }

    public function exportExcel()
    {
        $pasien = User::where('type', 0)->orderBy('name', 'asc')->get(['name', 'email', 'no_telepon', 'address']);
        return Excel::download(new PasienExport($pasien), 'data-pasien.xlsx');
    }
}
