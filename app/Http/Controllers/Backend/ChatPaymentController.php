<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ChatpaymentExport;
use App\Http\Controllers\Controller;
use App\Models\ChatPayment;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class ChatPaymentController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $id = auth()->user()->id;
            $userType = auth()->user()->type;
            if ($userType == "Pasien") {
                $chatpayments = Chatpayment::with(['user', 'dokter'])
                    ->orderBy('created_at', 'desc')
                    ->where('user_id', $id) // hanya menampilkan data milik user yang sedang login
                    ->get();
            } else if ($userType == "Dokter") {
                $chatpayments = Chatpayment::with(['user', 'dokter'])
                    ->orderBy('created_at', 'desc')
                    ->whereHas('dokter', function ($query) use ($id) {
                        $query->where('user_id', $id);
                    })
                    ->get();
            } else {
                $chatpayments = Chatpayment::with(['user', 'dokter'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            return DataTables::of($chatpayments)
                ->addIndexColumn()
                ->addColumn('pasien', function ($data) {
                    $name = $data->user->name;
                    return $name;
                })
                ->addColumn('dokter', function ($data) {
                    $name = $data->dokter->user->name;
                    return $name;
                })
                ->addColumn('no_telepon', function ($data) {
                    $no_telepon = $data->user->no_telepon;
                    return $no_telepon;
                })
                ->addColumn('waktu', function ($data) {
                    $waktuMulai = Carbon::createFromFormat('Y-m-d H:i:s', $data->waktu_mulai);
                    $waktuSelesai = Carbon::createFromFormat('Y-m-d H:i:s', $data->waktu_selesai);
                    $selisihWaktu = $waktuMulai->diffInSeconds(Carbon::now());

                    if ($selisihWaktu >= 0 && $selisihWaktu <= $waktuSelesai->diffInSeconds($waktuMulai)) {
                        $statusWaktu = '<span class="badge bg-success">Waktu Berjalan</span>';
                    } else {
                        $statusWaktu = '<span class="badge bg-danger">Waktu Habis</span>';
                    }

                    return $statusWaktu;
                })
                ->addColumn('aksi', function ($data) {
                    $btn = '<a class="btn btn-info btn-sm me-1" href="' . route('chatpayment.detail', $data->id) . '" ><i class="fa-solid fa-circle-info"></i></a>';

                    $waktuMulai = Carbon::createFromFormat('Y-m-d H:i:s', $data->waktu_mulai);
                    $waktuSelesai = Carbon::createFromFormat('Y-m-d H:i:s', $data->waktu_selesai);
                    $selisihWaktu = $waktuMulai->diffInSeconds(Carbon::now());
                    if ($selisihWaktu >= 0 && $selisihWaktu <= $waktuSelesai->diffInSeconds($waktuMulai)) {
                        if (auth()->user()->type != 'Dokter') {
                            $btn = $btn . '<a class="btn btn-success btn-sm me-1" href="' . url('chat-RSJuanda/' . $data->dokter->user->id) . '" target="_blank"><i class="fa-solid fa-comment"></i></i></a>';
                        }
                        if (auth()->user()->type == 'Dokter') {
                            $btn = $btn . '<a class="btn btn-success btn-sm me-1" href="' . url('chat-RSJuanda/' . $data->user->id) . '" target="_blank"><i class="fa-solid fa-comment"></i></i></a>';
                        }
                    }
                    if (auth()->user()->type != 'Pasien') {
                        $btn = $btn . '<button type="button" class="btn btn-danger btn-sm" data-id="' . $data->id . '" id="btnHapus"><i
                    class="mdi mdi-trash-can"></i></button>';
                    }
                    return $btn;
                })
                ->rawColumns(['aksi', 'status', 'waktu'])
                ->make(true);
        }
        return view('backend.chatpayment.index');
    }


    public function detail($id)
    {
        $chatpayment = Chatpayment::with(['user', 'dokter'])->findOrFail($id);
        $waktuMulai = Carbon::createFromFormat('Y-m-d H:i:s', $chatpayment->waktu_mulai);
        $waktuSelesai = Carbon::createFromFormat('Y-m-d H:i:s', $chatpayment->waktu_selesai);

        $selisihWaktu = $waktuMulai->diffInSeconds(Carbon::now());
        return view('backend.chatpayment.detail', compact(['chatpayment', 'waktuMulai', 'waktuSelesai', 'selisihWaktu']));
    }

    public function create()
    {
        $dokter = Dokter::with('user')->get();
        $pasien = Pasien::with('user')->get();
        return view('backend.chatpayment.add', compact(['pasien', 'dokter']));
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'pasien' => 'required|string',
                'dokter' => 'required|string',
                'biaya_chat' => 'required|string',
                'bukti_pembayaran' => 'required|image|mimes:jpg,png,jpeg,webp,svg',
            ],
            [
                'pasien.required' => 'Silakan isi pasien terlebih dahulu!',
                'dokter.required' => 'Silakan isi dokter terlebih dahulu!',
                'biaya_chat.required' => 'Silakan isi biaya chat terlebih dahulu!',
                'bukti_pembayaran.required' => 'Silakan isi bukti pembayaran terlebih dahulu!.',
                'bukti_pembayaran.image' => 'Berkas yang diunggah harus berupa gambar.',
                'bukti_pembayaran.mimes' => 'Berkas yang diunggah harus berupa salah satu dari jenis berikut: jpg, png, jpeg, webp, svg.',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $chatpayment = new ChatPayment;
            $chatpayment->user_id = $request->pasien;
            $chatpayment->dokter_id = $request->dokter;
            $chatpayment->biaya_chat = $request->biaya_chat;
            $chatpayment->waktu_mulai = Carbon::now();
            $chatpayment->waktu_selesai = Carbon::now()->addMinutes($request->biaya_chat / 800); // hitung waktu selesai
            $chatpayment->status = '0';
            // simpan file bukti pembayaran ke storage
            $file = $request->file('bukti_pembayaran');
            $filename = 'bukti_pembayaran_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('bukti_pembayaran', $filename);
            $chatpayment->bukti_pembayaran = $filename;

            $chatpayment->save();

            $user = User::find($request->pasien);
            $dokter = Dokter::with('user')->findOrFail($request->dokter);
            $biaya_chat = "Rp " . number_format($request->biaya_chat, 0, ',', '.');
            $nameStruk = "struk_transaksi_" . time() . ".pdf";

            return response()->json(['success' => 'Data berhasil ditambahkan', 'chatpayment' => $chatpayment, 'user' => $user, 'dokter' => $dokter, 'biaya_chat' => $biaya_chat, 'nameStruk' => $nameStruk]);
        }
    }

    public function destroy(Request $request)
    {
        $chatpayment = ChatPayment::findOrFail($request->id);
        $chatpayment->delete();
        return Response()->json(['chatpayment' => $chatpayment, 'success' => 'Data berhasil dihapus']);
    }

    public function printPDF()
    {
        $chatpayments = Chatpayment::with(['user', 'dokter'])->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('backend.chatpayment.printPDF', compact('chatpayments'));
        return $pdf->download('data-chatpayment-' . time() . '.pdf');
    }

    public function exportExcel()
    {
        $chatpayments = Chatpayment::with(['user', 'dokter'])->orderBy('created_at', 'desc')->get();
        return Excel::download(new ChatpaymentExport($chatpayments), 'data-chatpayments.xlsx');
    }
}
