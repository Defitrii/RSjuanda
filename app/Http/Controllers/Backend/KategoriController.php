<?php

namespace App\Http\Controllers\Backend;

use App\Exports\KategoriExport;
use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class KategoriController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $kategori = Kategori::orderBy('kode_kategori', 'asc')->get();
            return DataTables::of($kategori)
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
        return view('backend.kategori.index');
    }

    public function store(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:kategori,name,' . $id,
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
                $lastKode = Kategori::orderByDesc('created_at')->first();
                $nextKodeNumber = $lastKode ? intval(substr($lastKode->kode_kategori, -4)) + 1 : 1;
                $KategoriCode = sprintf('%04d', $nextKodeNumber);

                $kategori = new Kategori();
                $kategori->kode_kategori = $KategoriCode;
            } else {
                $kategori = Kategori::find($id);
                if ($kategori->kode_kategori != $request->kode_kategori) {
                    $request->merge(['kode_kategori' => $kategori->kode_kategori]);
                }
            }

            $kategori->name = $request->name;
            $kategori->save();

            return response()->json($kategori);
        }
    }

    public function edit($id)
    {
        $data = Kategori::where('id', $id)->first();
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $kategori = Kategori::where('id', $request->id)->delete();
        return Response()->json(['kategori' => $kategori, 'success' => 'Data berhasil dihapus']);
    }

    public function deleteMultiple(Request $request)
    {
        $id = $request->id;
        Kategori::whereIn('id', explode(",", $id))->delete();
        return response()->json(['success' => "Data berhasil dihapus"]);
    }

    public function printPDF()
    {
        $kategori = Kategori::all();

        $pdf = Pdf::loadView('backend.kategori.printPDF', compact('kategori'));
        return $pdf->download('data-kategori-' . time() . '.pdf');
    }

    public function exportExcel()
    {
        $kategori = Kategori::get(['kode_kategori', 'name']);
        return Excel::download(new KategoriExport($kategori), 'data-kategori.xlsx');
    }
}
