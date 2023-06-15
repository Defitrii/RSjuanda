<?php

namespace App\Http\Controllers\Backend;

use App\Exports\TransaksiHomecareExport;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Dokter;
use App\Models\Homecare;
use App\Models\Pasien;
use App\Models\Perawat;
use App\Models\Province;
use App\Models\Regency;
use App\Models\TransaksiHomecare;
use App\Models\User;
use App\Models\Village;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class TransaksiHomecareController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $id = auth()->user()->id;
            $userType = auth()->user()->type;
            if ($userType == "Dokter") {
                $transaksiHomecare = TransaksiHomecare::with('pasien', 'perawat', 'dokter', 'homecare')->where('dokter_id', $id)->orderBy('created_at', 'asc')->get();
            } else if ($userType == "Perawat") {
                $transaksiHomecare = TransaksiHomecare::with('pasien', 'perawat', 'dokter', 'homecare')->where('perawat_id', $id)->orderBy('created_at', 'asc')->get();
            } else if ($userType == "Pasien") {
                $transaksiHomecare = TransaksiHomecare::with('pasien', 'perawat', 'dokter', 'homecare')->where('pasien_id', $id)->orderBy('created_at', 'asc')->get();
            } else {
                $transaksiHomecare = TransaksiHomecare::with('pasien', 'perawat', 'dokter', 'homecare')->orderBy('created_at', 'asc')->get();
            }
            return DataTables::of($transaksiHomecare)
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
                    $layanan = $data->homecare->name;
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
                    if (auth()->user()->type != 'Pasien') {
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
                        $btn = $btn .  '<a href="' . route('transaksi-homecare.print', $data->id) . '" class="btn btn-secondary btn-sm"><i class="fa-solid fa-print"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['aksi', 'comboBox', 'status'])
                ->make(true);
        }
        return view('backend.transaksiHomecare.index');
    }

    public function detail(Request $request)
    {
        $transaksiHomecare = TransaksiHomecare::with('homecare')->findOrFail($request->id);
        $buktiPembayaran =  asset('storage/bukti-paket-homecare/' . $transaksiHomecare->bukti_pembayaran);
        $pasien = User::with('pasien')->find($transaksiHomecare->pasien_id);
        $perawat = User::with('perawat')->find($transaksiHomecare->perawat_id);
        $dokter = User::with('dokter')->find($transaksiHomecare->dokter_id);
        return response()->json(['pasien' => $pasien, 'perawat' => $perawat, 'dokter' => $dokter, 'transaksiHomecare' => $transaksiHomecare, 'buktiPembayaran' => $buktiPembayaran]);
    }

    public function getHomecare(Request $request)
    {
        $id_homecare = $request->id_homecare;
        $homecare = Homecare::findOrFail($id_homecare);
        return response()->json($homecare);
    }

    public function create()
    {
        $pasien = Pasien::join('users', 'users.id', '=', 'pasien.user_id')->orderBy('users.name', 'asc')->get();
        $perawat = Perawat::join('users', 'users.id', '=', 'perawat.user_id')->orderBy('users.name', 'asc')->where('status', '0')->get();
        $dokter = Dokter::join('users', 'users.id', '=', 'dokter.user_id')->orderBy('users.name', 'asc')->where('status', '0')->get();
        $homecare = Homecare::orderBy('name', 'asc')->get();
        return view('backend.transaksiHomecare.add', compact(['pasien', 'perawat', 'dokter', 'homecare']));
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
                'homecare' => 'required|string',
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
                'homecare.required' => 'Silakan pilih homecare terlebih dahulu!',
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
                    $request->file('bukti_pembayaran')->storeAs('bukti-paket-homecare/', 'Bukti Pembayaran - ' . $request->name . date('Ymd') . '.' . $guessExtension, 'public');

                    $paketHomecare = new TransaksiHomecare();
                    $paketHomecare->pasien_id = $request->pasien;
                    $paketHomecare->perawat_id = $request->perawat;
                    $paketHomecare->dokter_id = $request->dokter;
                    $paketHomecare->homecare_id = $request->homecare;
                    $paketHomecare->riwayat_penyakit = $request->riwayat_penyakit;
                    $paketHomecare->waktu = $request->waktu;
                    $paketHomecare->provinsi_id = $pasien->provinsi_id;
                    $paketHomecare->kabupaten_id = $pasien->kabupaten_id;
                    $paketHomecare->kecamatan_id = $pasien->kecamatan_id;
                    $paketHomecare->desa_id = $pasien->desa_id;
                    $paketHomecare->jarak = $request->jarak;
                    $paketHomecare->metode_pembayaran = $request->pembayaran;
                    $paketHomecare->bukti_pembayaran = 'Bukti Pembayaran - ' . $request->name . date('Ymd') . '.' . $guessExtension;
                    $paketHomecare->biaya_tambahan = $request->biaya_tambahan;
                    $paketHomecare->total_biaya = $request->total_biaya;
                    $paketHomecare->status = 1;
                    $paketHomecare->save();

                    return response()->json(['success' => 'Data barhasil ditambahkan']);
                }
            } else {
                $paketHomecare = new TransaksiHomecare();
                $paketHomecare->pasien_id = $request->pasien;
                $paketHomecare->perawat_id = $request->perawat;
                $paketHomecare->dokter_id = $request->dokter;
                $paketHomecare->homecare_id = $request->homecare;
                $paketHomecare->riwayat_penyakit = $request->riwayat_penyakit;
                $paketHomecare->waktu = $request->waktu;
                $paketHomecare->provinsi_id = $pasien->provinsi_id;
                $paketHomecare->kabupaten_id = $pasien->kabupaten_id;
                $paketHomecare->kecamatan_id = $pasien->kecamatan_id;
                $paketHomecare->desa_id = $pasien->desa_id;
                $paketHomecare->jarak = $request->jarak;
                $paketHomecare->metode_pembayaran = $request->pembayaran;
                $paketHomecare->biaya_tambahan = $request->biaya_tambahan;
                $paketHomecare->total_biaya = $request->total_biaya;
                $paketHomecare->status = 1;
                $paketHomecare->save();

                return response()->json(['success' => 'Data barhasil ditambahkan']);
            }
        }
    }

    public function destroy(Request $request)
    {
        $transaksiHomecare = TransaksiHomecare::findOrFail($request->id);

        if ($transaksiHomecare->bukti_pembayaran !== '') {
            Storage::delete('bukti-paket-homecare/' . $transaksiHomecare->bukti_pembayaran);
            $transaksiHomecare->delete();
        } else {
            $transaksiHomecare->delete();
        }

        return Response()->json(['transaksiHomecare' => $transaksiHomecare, 'success' => 'Data berhasil dihapus']);
    }

    public function deleteMultiple(Request $request)
    {
        $TransaksiHomecare = TransaksiHomecare::whereIn('id', explode(",", $request->id))->get();

        foreach ($TransaksiHomecare as $row) {
            if ($row->bukti_pembayaran !== '') {
                Storage::delete('bukti-paket-homecare/' . $row->bukti_pembayaran);
                $row->delete();
            } else {
                $row->delete();
            }
        }

        return response()->json(['success' => "Data berhasil dihapus"]);
    }

    public function aktif(Request $request)
    {
        $transaksiHomecare = TransaksiHomecare::findOrFail($request->id);
        $dokterId = User::with('dokter')->find($transaksiHomecare->dokter_id);
        $dokter = Dokter::find($dokterId->dokter->id);
        $dokter->update([
            'status' => 1,
        ]);

        $perawatId = User::with('perawat')->find($transaksiHomecare->perawat_id);
        $perawat = Perawat::find($perawatId->perawat->id);
        $perawat->update([
            'status' => 1,
        ]);

        $transaksiHomecare->update([
            'status' => 0,
        ]);

        return Response()->json(['transaksiHomecare' => $transaksiHomecare, 'success' => 'Pelayanan berhasil diaktifkan']);
    }

    public function nonaktif(Request $request)
    {
        $transaksiHomecare = TransaksiHomecare::findOrFail($request->id);
        $transaksiHomecare->waktu_selesai = Carbon::now('Asia/Jakarta');
        $transaksiHomecare->deskripsi_kegiatan = $request->deskripsi_kegiatan;
        $transaksiHomecare->update([
            'status' => 2,
        ]);

        $dokterId = User::with('dokter')->find($transaksiHomecare->dokter_id);
        $dokter = Dokter::find($dokterId->dokter->id);
        $dokter->update([
            'status' => 0,
        ]);

        $perawatId = User::with('perawat')->find($transaksiHomecare->perawat_id);
        $perawat = Perawat::find($perawatId->perawat->id);
        $perawat->update([
            'status' => 0,
        ]);
        return Response()->json(['transaksiHomecare' => $transaksiHomecare, 'success' => 'Pelayanan berhasil dinonaktifkan']);
    }

    // Print kwitansi
    public function print($id)
    {
        $data = TransaksiHomecare::with('pasien', 'perawat', 'dokter', 'homecare')->find($id);
        $dateString = $data->waktu;
        $dateObject = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
        $waktu = $dateObject->format('d/m/Y H:i:s');

        $pdf = FacadePdf::loadView('backend.transaksiHomecare.print', compact('data', 'waktu'));
        return $pdf->download('transaksi-paket-homecare-' . $data->pasien->name . '-' . time() . '.pdf');
    }

    public function printPDF()
    {
        $transaksiHomecare = TransaksiHomecare::with('pasien', 'perawat', 'dokter', 'homecare')->orderBy('created_at', 'asc')->get();

        $pdf = FacadePdf::loadView('backend.transaksiHomecare.printPDF', compact('transaksiHomecare'));
        return $pdf->download('data-transaksi-paket-homecare-' . time() . '.pdf');
    }

    public function exportExcel()
    {
        $transaksiHomecare = TransaksiHomecare::with('pasien', 'perawat', 'dokter', 'homecare')->orderBy('created_at', 'asc')->get();
        return Excel::download(new TransaksiHomecareExport($transaksiHomecare), 'data-transaksi-paket-homecare.xlsx');
    }
}
