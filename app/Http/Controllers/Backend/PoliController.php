<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PoliExport;
use App\Http\Controllers\Controller;
use App\Models\Poli;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class PoliController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $poli = Poli::orderBy('kode_poli', 'asc')->get();
            return DataTables::of($poli)
                ->addIndexColumn()
                ->addColumn('comboBox', function ($data) {
                    $comboBox = "<input type='checkbox' class='checkbox' data-id='" . $data->id . "'>";
                    return $comboBox;
                })
                ->addColumn('aksi', function ($data) {
                    $btn = '<button type="button" class="btn btn-warning btn-sm me-1" data-id="' . $data->id . '" id="btnEdit"><i
                    class="mdi mdi-pencil"></i></button>';
                    $btn = $btn . '<button type="button" class="btn btn-danger btn-sm" data-id="' . $data->id . '" id="btnHapus"><i
                    class="mdi mdi-trash-can"></i></button>';
                    return $btn;
                })
                ->rawColumns(['aksi', 'comboBox'])
                ->make(true);
        }
        return view('backend.poli.index');
    }

    public function store(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:poli,name,' . $id,
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'name.unique' => 'Nama sudah tersedia!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            if (!$id) {
                $lastKode = Poli::orderByDesc('created_at')->first();
                $nextKodeNumber = $lastKode ? intval(substr($lastKode->kode_poli, -4)) + 1 : 1;
                $PoliCode = sprintf('%04d', $nextKodeNumber);

                $poli = new Poli();
                $poli->kode_poli = $PoliCode;
            } else {
                $poli = Poli::find($id);
                if ($poli->kode_poli != $request->kode_poli) {
                    $request->merge(['kode_poli' => $poli->kode_poli]);
                }
            }

            $poli->name = $request->name;
            $poli->save();

            return response()->json($poli);
        }
    }

    public function edit($id)
    {
        $data = Poli::where('id', $id)->first();
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $poli = Poli::where('id', $request->id)->delete();
        return Response()->json(['poli' => $poli, 'success' => 'Data berhasil dihapus']);
    }

    public function deleteMultiple(Request $request)
    {
        $id = $request->id;
        Poli::whereIn('id', explode(",", $id))->delete();
        return response()->json(['success' => "Data berhasil dihapus"]);
    }

    public function printPDF()
    {
        $poli = Poli::all();

        $pdf = Pdf::loadView('backend.poli.printPDF', compact('poli'));
        return $pdf->download('data-poli-' . time() . '.pdf');
    }

    public function exportExcel()
    {
        $poli = Poli::get(['kode_poli', 'name']);
        return Excel::download(new PoliExport($poli), 'data-poli.xlsx');
    }
}
