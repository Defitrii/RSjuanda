<?php

namespace App\Http\Controllers\Backend;

use App\Exports\BayarExport;
use App\Http\Controllers\Controller;
use App\Models\Bayar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class BayarController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $bayar = Bayar::orderBy('kode_bayar', 'asc')->get();
            return DataTables::of($bayar)
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
        return view('backend.bayar.index');
    }

    public function store(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:bayar,name,' . $id,
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
                $lastKode = Bayar::orderByDesc('created_at')->first();
                $nextKodeNumber = $lastKode ? intval(substr($lastKode->kode_bayar, -4)) + 1 : 1;
                $BayarCode = sprintf('%04d', $nextKodeNumber);

                $bayar = new Bayar();
                $bayar->kode_bayar = $BayarCode;
            } else {
                $bayar = Bayar::find($id);
                if ($bayar->kode_bayar != $request->kode_bayar) {
                    $request->merge(['kode_bayar' => $bayar->kode_bayar]);
                }
            }

            $bayar->name = $request->name;
            $bayar->save();

            return response()->json($bayar);
        }
    }

    public function edit($id)
    {
        $data = Bayar::where('id', $id)->first();
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $kategori = Bayar::where('id', $request->id)->delete();
        return Response()->json(['kategori' => $kategori, 'success' => 'Data berhasil dihapus']);
    }

    public function deleteMultiple(Request $request)
    {
        $id = $request->id;
        Bayar::whereIn('id', explode(",", $id))->delete();
        return response()->json(['success' => "Data berhasil dihapus"]);
    }

    public function printPDF()
    {
        $bayar = Bayar::all();

        $pdf = Pdf::loadView('backend.bayar.printPDF', compact('bayar'));
        return $pdf->download('data-bayar-' . time() . '.pdf');
    }

    public function exportExcel()
    {
        $bayar = Bayar::get(['kode_bayar', 'name']);
        return Excel::download(new BayarExport($bayar), 'data-bayar.xlsx');
    }
}
