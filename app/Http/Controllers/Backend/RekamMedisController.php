<?php

namespace App\Http\Controllers\backend;

use App\Exports\RekamMedisExport;
use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class RekamMedisController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $userType = auth()->user()->type;
            if ($userType == 'Dokter') {
                $id = auth()->user()->id;
                $rekamMedis = RekamMedis::with('user', 'dokter')
                    ->orderBy('created_at', 'desc')
                    ->whereHas('dokter', function ($query) use ($id) {
                        $query->where('user_id', $id);
                    })
                    ->get();
            } else {
                $rekamMedis = RekamMedis::with(['user', 'dokter'])->get();
            }

            return DataTables::of($rekamMedis)
                ->addIndexColumn()
                ->addColumn('comboBox', function ($data) {
                    $comboBox = "<input type='checkbox' class='checkbox' data-id='" . $data->id . "'>";
                    return $comboBox;
                })
                ->addColumn('pasien', function ($data) {
                    return $data->user->name;
                })
                ->addColumn('aksi', function ($data) {
                    if (auth()->user()->type == 'Administrator') {
                        $btn = '<button type="button" class="btn btn-info btn-sm me-1" id="btn-detail" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fa-solid fa-circle-info"></i></button>';
                        return $btn;
                    } else {
                        $btn = '<button type="button" class="btn btn-info btn-sm me-1" id="btn-detail" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fa-solid fa-circle-info"></i></button>';
                        $btn = $btn . '<a class="btn btn-warning btn-sm me-1" href="' . url('rekam-medis/' . $data->id . '/edit') . '" ><i
                    class="mdi mdi-pencil"></i></a>';
                        $btn = $btn . '<button type="button" class="btn btn-danger btn-sm" data-id="' . $data->id . '" id="btnHapus"><i
                    class="mdi mdi-trash-can"></i></button>';
                        return $btn;
                    }
                })
                ->rawColumns(['aksi', 'comboBox'])
                ->make(true);
        }
        return view('backend.rekam_medis.index');
    }

    public function detail(Request $request)
    {
        $rekamMedis = RekamMedis::with(['user', 'dokter'])->findOrFail($request->id);
        $namaDokter = $rekamMedis->dokter->user->name;
        return response()->json(['rekam_medis' => $rekamMedis, 'namaDokter' => $namaDokter]);
    }

    public function create()
    {
        $id = auth()->user()->id;
        $dokter = User::with('dokter')->findOrFail($id);
        $pasien = User::where('type', 0)->orderBy('name', 'asc')->get();
        return view('backend.rekam_medis.add', compact(['pasien', 'dokter']));
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'pasien' => 'required|string',
                'tanggal_kunjungan' => 'required|string',
                'keluhan' => 'required|string',
                'diagnosa' => 'required|string',
                'resep_obat' => 'required|string',
            ],
            [
                'pasien.required' => 'Silakan pilih pasien terlebih dahulu!',
                'tanggal_kunjungan.required' => 'Silakan isi tanggal kunjungan terlebih dahulu!',
                'keluhan.required' => 'Silakan isi keluhan terlebih dahulu!',
                'diagnosa.required' => 'Silakan isi diagnosa terlebih dahulu!',
                'resep_obat.required' => 'Silakan isi resep obat terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $lastKode = RekamMedis::orderByDesc('created_at')->first();
            $nextNumber = $lastKode ? intval(substr($lastKode->kode_rekam_medis, -4)) + 1 : 1;
            $kode = 'RKM' . sprintf('%04d', $nextNumber);

            $rekamMedis = new RekamMedis();

            $rekamMedis->kode_rekam_medis = $kode;
            $rekamMedis->user_id = $request->pasien;
            $rekamMedis->dokter_id = $request->dokter;
            $rekamMedis->tanggal_kunjungan = $request->tanggal_kunjungan;
            $rekamMedis->keluhan = $request->keluhan;
            $rekamMedis->diagnosa = $request->diagnosa;
            $rekamMedis->resep_obat = $request->resep_obat;
            $rekamMedis->catatan_tambahan = $request->catatan_tambahan;
            $rekamMedis->save();

            return response()->json(['success' => 'Data barhasil ditambahkan']);
        }
    }

    public function edit($id)
    {
        $pasien = User::where('type', 0)->orderBy('name', 'asc')->get();
        $rekamMedis = RekamMedis::with(['user', 'dokter'])->findOrFail($id);
        return view('backend.rekam_medis.edit', compact(['pasien', 'rekamMedis']));
    }


    public function update(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'pasien' => 'required|string',
                'tanggal_kunjungan' => 'required|string',
                'keluhan' => 'required|string',
                'diagnosa' => 'required|string',
                'resep_obat' => 'required|string',
            ],
            [
                'pasien.required' => 'Silakan pilih pasien terlebih dahulu!',
                'tanggal_kunjungan.required' => 'Silakan isi tanggal kunjungan terlebih dahulu!',
                'keluhan.required' => 'Silakan isi keluhan terlebih dahulu!',
                'diagnosa.required' => 'Silakan isi diagnosa terlebih dahulu!',
                'resep_obat.required' => 'Silakan isi resep obat terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $rekamMedis = RekamMedis::find($id);

            $rekamMedis->user_id = $request->pasien;
            $rekamMedis->dokter_id = $request->dokter;
            $rekamMedis->tanggal_kunjungan = $request->tanggal_kunjungan;
            $rekamMedis->keluhan = $request->keluhan;
            $rekamMedis->diagnosa = $request->diagnosa;
            $rekamMedis->resep_obat = $request->resep_obat;
            $rekamMedis->catatan_tambahan = $request->catatan_tambahan;
            $rekamMedis->save();

            return response()->json(['success' => 'Data barhasil diubah']);
        }
    }

    public function destroy(Request $request)
    {
        $rekamMedis = RekamMedis::findOrFail($request->id);
        $rekamMedis->delete();

        return Response()->json(['rekamMedis' => $rekamMedis, 'success' => 'Data berhasil dihapus']);
    }

    public function deleteMultiple(Request $request)
    {
        $rekamMedis = RekamMedis::whereIn('id', explode(",", $request->id))->get();

        foreach ($rekamMedis as $row) {
            $row->delete();
        }

        return response()->json(['success' => "Data berhasil dihapus"]);
    }

    public function printPDF()
    {
        $rekamMedis = RekamMedis::with(['user', 'dokter'])->get();

        $pdf = Pdf::loadView('backend.rekam_medis.printPDF', compact('rekamMedis'));
        return $pdf->download('data-rekam-medis-' . time() . '.pdf');
    }

    public function exportExcel()
    {
        $rekamMedis = RekamMedis::with(['user', 'dokter'])->get();
        return Excel::download(new RekamMedisExport($rekamMedis), 'data-rekam-medis.xlsx');
    }
}
