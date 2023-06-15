<?php

namespace App\Http\Controllers\Backend;

use App\Exports\TransaksiFisioterapiExport;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Dokter;
use App\Models\Fisioterapi;
use App\Models\Pasien;
use App\Models\Perawat;
use App\Models\Province;
use App\Models\Regency;
use App\Models\TransaksiFisioterapi;
use App\Models\User;
use App\Models\Village;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class TransaksiFisioterapiController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $id = auth()->user()->id;
            $userType = auth()->user()->type;
            if ($userType == "Dokter") {
                $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->where('dokter_id', $id)->orderBy('created_at', 'asc')->get();
            } else if ($userType == "Perawat") {
                $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->where('perawat_id', $id)->orderBy('created_at', 'asc')->get();
            } else if ($userType == "Pasien") {
                $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->where('pasien_id', $id)->orderBy('created_at', 'asc')->get();
            } else {
                $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->orderBy('created_at', 'asc')->get();
            }
            return DataTables::of($transaksiFisioterapi)
                ->addIndexColumn()
                ->addColumn('comboBox', function ($data) {
                    $comboBox = "<input type='checkbox' class='checkbox' data-id='" . $data->id . "'>";
                    return $comboBox;
                })
                ->addColumn('pasien', function ($data) {
                    $pasien = $data->pasien->name;
                    return $pasien;
                })
                ->addColumn('perawat', function ($data) {
                    $perawat = $data->perawat->name;
                    return $perawat;
                })
                ->addColumn('dokter', function ($data) {
                    $dokter = $data->dokter->name;
                    return $dokter;
                })
                ->addColumn('layanan', function ($data) {
                    $layanan = $data->fisioterapi->name;
                    return $layanan;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == '0') {
                        $badgeStatus = '<span class="badge bg-success">Aktif</span>';
                        return $badgeStatus;
                    } else if ($data->status == '1') {
                        $badgeStatus = '<span class="badge bg-warning">Pending</span>';
                        return $badgeStatus;
                    } else {
                        $badgeStatus = '<span class="badge bg-danger">Tidak Aktif</span>';
                        return $badgeStatus;
                    }
                })
                ->addColumn('aksi', function ($data) {
                    $btn = '<button type="button" class="btn btn-info btn-sm me-1" id="btn-detail" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fa-solid fa-circle-info"></i></button>';
                    if (auth()->user()->type != "Pasien") {
                        if ($data->status != '0') {
                            $btn = $btn . '<button type="button" class="btn btn-danger btn-sm me-1" data-id="' . $data->id . '" id="btnHapus"><i
                            class="mdi mdi-trash-can"></i></button>';
                        }
                        if ($data->status == '0') {
                            $btn = $btn .  '<button type="button" class="btn btn-warning btn-sm me-1" data-id="' . $data->id . '" id="btnNonaktif" data-bs-toggle="modal" data-bs-target="#nonaktifModal"><i class="fa-solid fa-xmark"></i></button>';
                        }
                        if ($data->status == '1') {
                            $btn = $btn .  '<button type="button" class="btn btn-success btn-sm me-1" data-id="' . $data->id . '" id="btnAktif"><i class="fa-solid fa-check"></i></button>';
                        }
                    }
                    if ($data->status != '1') {
                        $btn = $btn .  '<a href="' . route('transaksi-fisioterapi.print', $data->id) . '" class="btn btn-secondary btn-sm"><i class="fa-solid fa-print"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['aksi', 'comboBox', 'status'])
                ->make(true);
        }
        return view('backend.transaksiFisioterapi.index');
    }

    public function detail(Request $request)
    {
        $transaksiFisioterapi = TransaksiFisioterapi::with('fisioterapi')->findOrFail($request->id);
        $buktiPembayaran =  asset('storage/bukti-fisioterapi/' . $transaksiFisioterapi->bukti_pembayaran);
        $pasien = User::with('pasien')->find($transaksiFisioterapi->pasien_id);
        $perawat = User::with('perawat')->find($transaksiFisioterapi->perawat_id);
        $dokter = User::with('dokter')->find($transaksiFisioterapi->dokter_id);
        return response()->json(['pasien' => $pasien, 'perawat' => $perawat, 'dokter' => $dokter, 'transaksiFisioterapi' => $transaksiFisioterapi, 'buktiPembayaran' => $buktiPembayaran]);
    }

    public function getFisioterapi(Request $request)
    {
        $id_fisioterapi = $request->id_fisioterapi;
        $fisioterapi = Fisioterapi::findOrFail($id_fisioterapi);
        return response()->json($fisioterapi);
    }

    public function create()
    {
        $pasien = Pasien::join('users', 'users.id', '=', 'pasien.user_id')->orderBy('users.name', 'asc')->get();
        $perawat = Perawat::join('users', 'users.id', '=', 'perawat.user_id')->orderBy('users.name', 'asc')->where('status', '0')->get();
        $dokter = Dokter::join('users', 'users.id', '=', 'dokter.user_id')->orderBy('users.name', 'asc')->where('status', '0')->get();
        $fisioterapi = Fisioterapi::orderBy('name', 'asc')->get();
        return view('backend.transaksiFisioterapi.add', compact(['pasien', 'perawat', 'dokter', 'fisioterapi']));
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'pasien' => 'required|string',
                'perawat' => 'required|string',
                'dokter' => 'required|string',
                'riwayat_penyakit' => 'required|string',
                'waktu' => 'required|string',
                'jarak' => 'required|numeric',
                'fisioterapi' => 'required|string',
                'bukti_pembayaran' => 'image|mimes:jpg,png,jpeg,webp,svg',
                'pembayaran' => 'required|string',
            ],
            [
                'pasien.required' => 'Silakan pilih pasien terlebih dahulu!',
                'perawat.required' => 'Silakan pilih perawat terlebih dahulu!',
                'dokter.required' => 'Silakan pilih dokter terlebih dahulu!',
                'riwayat_penyakit.required' => 'Silakan isi riwayat penyakit terlebih dahulu!',
                'waktu.required' => 'Silakan isi waktu terlebih dahulu!',
                'jarak.required' => 'Silakan isi jarak terlebih dahulu!',
                'fisioterapi.required' => 'Silakan pilih fisioterapi terlebih dahulu!',
                'bukti_pembayaran.image' => 'File harus berupa gambar!',
                'bukti_pembayaran.mimes' => 'Pilihan gambar yang diunggah harus dalam format JPG, PNG, JPEG, WEBP, atau SVG.',
                'pembayaran.required' => 'Silakan pilih metode pembayaran terlebih dahulu!',
            ]
        );


        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $pasien = User::find($request->pasien);
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                if ($file->isValid()) {
                    $guessExtension = $request->file('bukti_pembayaran')->guessExtension();
                    $request->file('bukti_pembayaran')->storeAs('bukti-fisioterapi/', 'Bukti Pembayaran - ' . $request->name . date('Ymd') . '.' . $guessExtension, 'public');

                    $fisioterapi = new TransaksiFisioterapi();
                    $fisioterapi->pasien_id = $request->pasien;
                    $fisioterapi->perawat_id = $request->perawat;
                    $fisioterapi->dokter_id = $request->dokter;
                    $fisioterapi->fisioterapi_id = $request->fisioterapi;
                    $fisioterapi->riwayat_penyakit = $request->riwayat_penyakit;
                    $fisioterapi->waktu = $request->waktu;
                    $fisioterapi->provinsi_id = $pasien->provinsi_id;
                    $fisioterapi->kabupaten_id = $pasien->kabupaten_id;
                    $fisioterapi->kecamatan_id = $pasien->kecamatan_id;
                    $fisioterapi->desa_id = $pasien->desa_id;
                    $fisioterapi->jarak = $request->jarak;
                    $fisioterapi->metode_pembayaran = $request->pembayaran;
                    $fisioterapi->bukti_pembayaran = 'Bukti Pembayaran - ' . $request->name . date('Ymd') . '.' . $guessExtension;
                    $fisioterapi->biaya_tambahan = $request->biaya_tambahan;
                    $fisioterapi->total_biaya = $request->total_biaya;
                    $fisioterapi->status = 1;
                    $fisioterapi->save();

                    return response()->json(['success' => 'Data barhasil ditambahkan']);
                }
            } else {
                $fisioterapi = new TransaksiFisioterapi();
                $fisioterapi->pasien_id = $request->pasien;
                $fisioterapi->perawat_id = $request->perawat;
                $fisioterapi->dokter_id = $request->dokter;
                $fisioterapi->fisioterapi_id = $request->fisioterapi;
                $fisioterapi->riwayat_penyakit = $request->riwayat_penyakit;
                $fisioterapi->waktu = $request->waktu;
                $fisioterapi->provinsi_id = $pasien->provinsi_id;
                $fisioterapi->kabupaten_id = $pasien->kabupaten_id;
                $fisioterapi->kecamatan_id = $pasien->kecamatan_id;
                $fisioterapi->desa_id = $pasien->desa_id;
                $fisioterapi->jarak = $request->jarak;
                $fisioterapi->metode_pembayaran = $request->pembayaran;
                $fisioterapi->biaya_tambahan = $request->biaya_tambahan;
                $fisioterapi->total_biaya = $request->total_biaya;
                $fisioterapi->status = 1;
                $fisioterapi->save();

                return response()->json(['success' => 'Data barhasil ditambahkan']);
            }
        }
    }

    public function destroy(Request $request)
    {
        $transaksiFisioterapi = TransaksiFisioterapi::findOrFail($request->id);

        if ($transaksiFisioterapi->bukti_pembayaran !== '') {
            Storage::delete('bukti-fisioterapi/' . $transaksiFisioterapi->bukti_pembayaran);
            $transaksiFisioterapi->delete();
        } else {
            $transaksiFisioterapi->delete();
        }

        return response()->json(['transaksiFisioterapi' => $transaksiFisioterapi, 'success' => 'Data berhasil dihapus']);
    }

    public function deleteMultiple(Request $request)
    {
        $transaksiFisioterapi = TransaksiFisioterapi::whereIn('id', explode(",", $request->id))->get();

        foreach ($transaksiFisioterapi as $row) {
            if ($row->bukti_pembayaran !== '') {
                Storage::delete('bukti-fisioterapi/' . $row->bukti_pembayaran);
                $row->delete();
            } else {
                $row->delete();
            }
        }

        return response()->json(['success' => "Data berhasil dihapus"]);
    }

    public function aktif(Request $request)
    {
        $transaksiFisioterapi = TransaksiFisioterapi::findOrFail($request->id);
        $dokterId = User::with('dokter')->find($transaksiFisioterapi->dokter_id);
        $dokter = Dokter::find($dokterId->dokter->id);
        $dokter->update([
            'status' => 1,
        ]);

        $perawatId = User::with('perawat')->find($transaksiFisioterapi->perawat_id);
        $perawat = Perawat::find($perawatId->perawat->id);
        $perawat->update([
            'status' => 1,
        ]);

        $transaksiFisioterapi->update([
            'status' => 0,
        ]);

        return Response()->json(['transaksiFisioterapi' => $transaksiFisioterapi, 'success' => 'Pelayanan berhasil diaktifkan']);
    }

    public function nonaktif(Request $request)
    {
        $transaksiFisioterapi = TransaksiFisioterapi::findOrFail($request->id);
        $transaksiFisioterapi->waktu_selesai = Carbon::now('Asia/Jakarta');
        $transaksiFisioterapi->deskripsi_kegiatan = $request->deskripsi_kegiatan;
        $transaksiFisioterapi->update([
            'status' => 2,
        ]);

        $dokterId = User::with('dokter')->find($transaksiFisioterapi->dokter_id);
        $dokter = Dokter::find($dokterId->dokter->id);
        $dokter->update([
            'status' => 0,
        ]);

        $perawatId = User::with('perawat')->find($transaksiFisioterapi->perawat_id);
        $perawat = Perawat::find($perawatId->perawat->id);
        $perawat->update([
            'status' => 0,
        ]);
        return Response()->json(['transaksiFisioterapi' => $transaksiFisioterapi, 'success' => 'Pelayanan berhasil dinonaktifkan']);
    }

    public function print($id)
    {
        $data = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->find($id);
        $dateString = $data->waktu;
        $dateObject = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
        $waktu = $dateObject->format('d/m/Y H:i:s');

        $pdf = Pdf::loadView('backend.transaksiFisioterapi.print', compact('data', 'waktu'));
        return $pdf->download('transaksi-fisioterapi-' . $data->pasien->name . '-' . time() . '.pdf');
    }

    public function printPDF()
    {
        $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->orderBy('created_at', 'asc')->get();

        $pdf = Pdf::loadView('backend.transaksiFisioterapi.printPDF', compact('transaksiFisioterapi'));
        return $pdf->download('data-transaksi-fisioterapi-' . time() . '.pdf');
    }

    public function exportExcel()
    {
        $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->orderBy('created_at', 'asc')->get();
        return Excel::download(new TransaksiFisioterapiExport($transaksiFisioterapi), 'data-transaksi-fisioterapi.xlsx');
    }
}
