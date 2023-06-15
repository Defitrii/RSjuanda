<?php

namespace App\Http\Controllers\Backend;

use App\Exports\TransaksiHomecarePerawatExport;
use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Pasien;
use App\Models\Perawat;
use App\Models\TransaksiHomecarePerawat;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class TransaksiHomecarePerawatController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $id = auth()->user()->id;
            $userType = auth()->user()->type;
            if ($userType == "Pasien") {
                $transaksiHomecare = TransaksiHomecarePerawat::with('pasien', 'perawat')->where('pasien_id', $id)->orderBy('created_at', 'asc')->get();
            } else if ($userType == "Perawat") {
                $transaksiHomecare = TransaksiHomecarePerawat::with('pasien', 'perawat')->where('perawat_id', $id)->orderBy('created_at', 'asc')->get();
            } else {
                $transaksiHomecare = TransaksiHomecarePerawat::with('pasien', 'perawat')->orderBy('created_at', 'asc')->get();
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
                    }
                    if (auth()->user()->type != 'Pasien') {
                        if ($data->status == '0') {
                            $btn = $btn .  '<button type="button" class="btn btn-warning btn-sm me-1" data-id="' . $data->id . '" id="btnNonaktif" data-bs-toggle="modal" data-bs-target="#nonaktifModal"><i class="fa-solid fa-xmark"></i></button>';
                        }
                        if ($data->status == '1') {
                            $btn = $btn .  '<button type="button" class="btn btn-success btn-sm me-1" data-id="' . $data->id . '" id="btnAktif"><i class="fa-solid fa-check"></i></button>';
                        }
                    }
                    if ($data->status != '1') {
                        $btn = $btn .  '<a href="' . route('transaksi-homecare-perawat.print', $data->id) . '" class="btn btn-secondary btn-sm"><i class="fa-solid fa-print"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['aksi', 'comboBox', 'status'])
                ->make(true);
        }
        return view('backend.transaksiHomecarePerawat.index');
    }

    public function detail(Request $request)
    {
        $transaksiHomecare = TransaksiHomecarePerawat::findOrFail($request->id);
        $buktiPembayaran = asset('storage/bukti-homecare/' . $transaksiHomecare->bukti_pembayaran);
        $pasien = User::with('pasien')->find($transaksiHomecare->pasien_id);
        $perawat = User::with('perawat')->find($transaksiHomecare->perawat_id);
        return response()->json(['pasien' => $pasien, 'perawat' => $perawat, 'transaksiHomecare' => $transaksiHomecare, 'buktiPembayaran' => $buktiPembayaran]);
    }

    public function getHomecarePrice(Request $request)
    {
        $homecareId = $request->homecare_id;
        $homecare = Layanan::findOrFail($homecareId);
        return response()->json(['price' => $homecare->harga]);
    }

    public function create()
    {
        $pasien = Pasien::join('users', 'users.id', '=', 'pasien.user_id')->orderBy('users.name', 'asc')->get();
        $perawat = Perawat::join('users', 'users.id', '=', 'perawat.user_id')->orderBy('users.name', 'asc')->where('status', '0')->get()->filter(function ($perawat) {
            return $perawat->transaksi()->whereDate('waktu', date('Y-m-d'))->count() == 0;
        });
        $homecare = Layanan::orderBy('name', 'asc')->get();
        return view('backend.transaksiHomecarePerawat.add', compact(['pasien', 'perawat', 'homecare']));
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'pasien' => 'required|string',
                'perawat' => 'required|string',
                'riwayat_penyakit' => 'required|string',
                'waktu' => 'required|string',
                'jarak' => 'required|numeric',
                'homecare' => 'required|array',
                'bukti_pembayaran' => 'image|mimes:jpg,png,jpeg,webp,svg',
                'pembayaran' => 'required|string',
            ],
            [
                'pasien.required' => 'Silakan pilih pasien terlebih dahulu!',
                'perawat.required' => 'Silakan pilih perawat terlebih dahulu!',
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
                    $request->file('bukti_pembayaran')->storeAs('bukti-homecare/', 'Bukti Pembayaran - ' . $request->name . date('Ymd') . '.' . $guessExtension, 'public');

                    $homecare = new TransaksiHomecarePerawat();
                    $homecare->pasien_id = $request->pasien;
                    $homecare->perawat_id = $request->perawat;
                    $homecare->homecare = implode(', ', $request->homecare);
                    $homecare->riwayat_penyakit = $request->riwayat_penyakit;
                    $homecare->waktu = $request->waktu;
                    $homecare->provinsi_id = $pasien->provinsi_id;
                    $homecare->kabupaten_id = $pasien->kabupaten_id;
                    $homecare->kecamatan_id = $pasien->kecamatan_id;
                    $homecare->desa_id = $pasien->desa_id;
                    $homecare->jarak = $request->jarak;
                    $homecare->metode_pembayaran = $request->pembayaran;
                    $homecare->bukti_pembayaran = 'Bukti Pembayaran - ' . $request->name . date('Ymd') . '.' . $guessExtension;
                    $homecare->biaya_tambahan = $request->biaya_tambahan;
                    $homecare->total_biaya = $request->total_biaya;
                    $homecare->status = 1;
                    $homecare->save();

                    return response()->json(['success' => 'Data barhasil ditambahkan']);
                }
            } else {
                $homecare = new TransaksiHomecarePerawat();
                $homecare->pasien_id = $request->pasien;
                $homecare->perawat_id = $request->perawat;
                $homecare->homecare = implode(', ', $request->homecare);
                $homecare->riwayat_penyakit = $request->riwayat_penyakit;
                $homecare->waktu = $request->waktu;
                $homecare->provinsi_id = $pasien->provinsi_id;
                $homecare->kabupaten_id = $pasien->kabupaten_id;
                $homecare->kecamatan_id = $pasien->kecamatan_id;
                $homecare->desa_id = $pasien->desa_id;
                $homecare->jarak = $request->jarak;
                $homecare->metode_pembayaran = $request->pembayaran;
                $homecare->biaya_tambahan = $request->biaya_tambahan;
                $homecare->total_biaya = $request->total_biaya;
                $homecare->status = 1;
                $homecare->save();

                return response()->json(['success' => 'Data barhasil ditambahkan']);
            }
        }
    }

    public function destroy(Request $request)
    {
        $transaksiHomecare = TransaksiHomecarePerawat::findOrFail($request->id);

        if ($transaksiHomecare->bukti_pembayaran !== '') {
            Storage::delete('bukti-homecare/' . $transaksiHomecare->bukti_pembayaran);
            $transaksiHomecare->delete();
        } else {
            $transaksiHomecare->delete();
        }

        return Response()->json(['transaksiHomecare' => $transaksiHomecare, 'success' => 'Data berhasil dihapus']);
    }

    public function deleteMultiple(Request $request)
    {
        $TransaksiHomecare = TransaksiHomecarePerawat::whereIn('id', explode(",", $request->id))->get();

        foreach ($TransaksiHomecare as $row) {
            if ($row->bukti_pembayaran !== '') {
                Storage::delete('bukti-homecare/' . $row->bukti_pembayaran);
                $row->delete();
            } else {
                $row->delete();
            }
        }

        return response()->json(['success' => "Data berhasil dihapus"]);
    }

    public function aktif(Request $request)
    {
        $transaksiHomecare = TransaksiHomecarePerawat::findOrFail($request->id);

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
        $transaksiHomecare = TransaksiHomecarePerawat::findOrFail($request->id);
        $transaksiHomecare->waktu_selesai = Carbon::now('Asia/Jakarta');
        $transaksiHomecare->deskripsi_kegiatan = $request->deskripsi_kegiatan;
        $transaksiHomecare->update([
            'status' => 2,
        ]);

        $perawatId = User::with('perawat')->find($transaksiHomecare->perawat_id);
        $perawat = Perawat::find($perawatId->perawat->id);
        $perawat->update([
            'status' => 0,
        ]);
        return Response()->json(['transaksiHomecare' => $transaksiHomecare, 'success' => 'Pelayanan berhasil dinonaktifkan']);
    }

    public function print($id)
    {
        $data = TransaksiHomecarePerawat::with('pasien', 'perawat')->find($id);
        $homecare = explode(', ', $data->homecare);
        $hargaLayanan = [];

        foreach ($homecare as $namaLayanan) {
            $hargaLayanan[$namaLayanan] = Layanan::where('name', $namaLayanan)->value('harga');
        }

        $dateString = $data->waktu;
        $dateObject = DateTime::createFromFormat('Y-m-d H:i:s', $dateString);
        $waktu = $dateObject->format('d/m/Y H:i:s');

        $pdf = Pdf::loadView('backend.transaksiHomecarePerawat.print', compact('data', 'waktu', 'hargaLayanan'));
        return $pdf->download('transaksi-homecare-' . $data->pasien->name . '-' . time() . '.pdf');
    }

    public function printPDF()
    {
        $transaksiHomecare = TransaksiHomecarePerawat::with('pasien', 'perawat')->orderBy('created_at', 'asc')->get();

        $pdf = Pdf::loadView('backend.transaksiHomecarePerawat.printPDF', compact('transaksiHomecare'));
        return $pdf->download('data-transaksi-homecare-' . time() . '.pdf');
    }

    public function exportExcel()
    {
        $transaksiHomecare = TransaksiHomecarePerawat::with('pasien', 'perawat')->orderBy('created_at', 'asc')->get();
        return Excel::download(new TransaksiHomecarePerawatExport($transaksiHomecare), 'data-transaksi-homecare.xlsx');
    }
}
