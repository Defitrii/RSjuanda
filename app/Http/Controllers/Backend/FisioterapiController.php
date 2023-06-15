<?php

namespace App\Http\Controllers\Backend;

use App\Exports\FisioterapiExport;
use App\Http\Controllers\Controller;
use App\Models\Fisioterapi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class FisioterapiController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $fisioterapi = Fisioterapi::orderBy('kode_fisioterapi', 'asc')->get();
            return DataTables::of($fisioterapi)
                ->addIndexColumn()
                ->addColumn('harga', function ($data) {
                    $harga = "Rp " . number_format($data->harga, 0, ',', '.');
                    return $harga;
                })
                ->addColumn('comboBox', function ($data) {
                    $comboBox = "<input type='checkbox' class='checkbox' data-id='" . $data->id . "'>";
                    return $comboBox;
                })
                ->addColumn('aksi', function ($data) {
                    $btn = '<button type="button" class="btn btn-info btn-sm me-1" id="btn-detail" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="fa-solid fa-circle-info"></i></button>';
                    $btn = $btn . '<button type="button" class="btn btn-warning btn-sm me-1" data-id="' . $data->id . '" id="btnEdit"><i
                    class="mdi mdi-pencil"></i></button>';
                    $btn = $btn . '<button type="button" class="btn btn-danger btn-sm" data-id="' . $data->id . '" id="btnHapus"><i
                    class="mdi mdi-trash-can"></i></button>';
                    return $btn;
                })
                ->rawColumns(['aksi', 'comboBox'])
                ->make(true);
        }
        return view('backend.fisioterapi.index');
    }

    public function detail(Request $request)
    {
        $fisioterapi = Fisioterapi::findOrFail($request->id);
        return response()->json(['fisioterapi' => $fisioterapi]);
    }

    public function store(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:fisioterapi,name,' . $id,
                'deskripsi' => 'required',
                'harga' => 'required',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu!',
                'name.unique' => 'Fisioterapi sudah tersedia!',
                'deskripsi.required' => 'Silakan isi deskripsi terlebih dahulu!',
                'harga.required' => 'Silakan isi harga terlebih dahulu!',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            if (!$id) {
                $lastFisioterapi = Fisioterapi::orderByDesc('created_at')->first();
                $nextFisioterapiNumber = $lastFisioterapi ? intval(substr($lastFisioterapi->kode_fisioterapi, -4)) + 1 : 1;
                $fisioterapiCode = 'FST' . sprintf('%04d', $nextFisioterapiNumber);

                $fisioterapi = new Fisioterapi();
                $fisioterapi->kode_fisioterapi = $fisioterapiCode;
            } else {
                $fisioterapi = Fisioterapi::find($id);
                if ($fisioterapi->kode_fisioterapi != $request->kode_fisioterapi) {
                    $request->merge(['kode_fisioterapi' => $fisioterapi->kode_fisioterapi]);
                }
            }

            $fisioterapi->name = $request->name;
            $fisioterapi->deskripsi = $request->deskripsi;
            $fisioterapi->harga = $request->harga;
            $fisioterapi->save();

            return response()->json($fisioterapi);
        }
    }

    public function edit($id)
    {
        $data = Fisioterapi::where('id', $id)->first();
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $kategori = Fisioterapi::where('id', $request->id)->delete();
        return Response()->json(['kategori' => $kategori, 'success' => 'Data berhasil dihapus']);
    }

    public function deleteMultiple(Request $request)
    {
        $id = $request->id;
        Fisioterapi::whereIn('id', explode(",", $id))->delete();
        return response()->json(['success' => "Data berhasil dihapus"]);
    }

    public function printPDF()
    {
        $fisioterapi = Fisioterapi::all();

        $pdf = Pdf::loadView('backend.fisioterapi.printPDF', compact('fisioterapi'));
        return $pdf->download('data-fisioterapi-' . time() . '.pdf');
    }

    public function exportExcel()
    {
        $fisioterapi = Fisioterapi::get(['kode_fisioterapi', 'name', 'harga']);
        return Excel::download(new FisioterapiExport($fisioterapi), 'data-fisioterapi.xlsx');
    }
}
