<?php

namespace App\Http\Controllers\Backend;

use App\Exports\JabatanExport;
use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class JabatanController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $jabatan = Jabatan::orderBy('kode_jabatan', 'asc')->get();
            return DataTables::of($jabatan)
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
        return view('backend.jabatan.index');
    }

    public function store(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:jabatan,name,' . $id,
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
                $lastKode = Jabatan::orderByDesc('created_at')->first();
                $nextKodeNumber = $lastKode ? intval(substr($lastKode->kode_jabatan, -4)) + 1 : 1;
                $JabatanCode = sprintf('%04d', $nextKodeNumber);

                $jabatan = new Jabatan();
                $jabatan->kode_jabatan = $JabatanCode;
            } else {
                $jabatan = Jabatan::find($id);
                if ($jabatan->kode_jabatan != $request->kode_jabatan) {
                    $request->merge(['kode_jabatan' => $jabatan->kode_jabatan]);
                }
            }

            $jabatan->name = $request->name;
            $jabatan->save();

            return response()->json($jabatan);
        }
    }

    public function edit($id)
    {
        $data = Jabatan::where('id', $id)->first();
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $jabatan = Jabatan::where('id', $request->id)->delete();
        return Response()->json(['jabatan' => $jabatan, 'success' => 'Data berhasil dihapus']);
    }

    public function deleteMultiple(Request $request)
    {
        $id = $request->id;
        Jabatan::whereIn('id', explode(",", $id))->delete();
        return response()->json(['success' => "Data berhasil dihapus"]);
    }

    public function printPDF()
    {
        $jabatan = Jabatan::all();

        $pdf = Pdf::loadView('backend.jabatan.printPDF', compact('jabatan'));
        return $pdf->download('data-jabatan-' . time() . '.pdf');
    }

    public function exportExcel()
    {
        $jabatan = Jabatan::get(['kode_jabatan', 'name']);
        return Excel::download(new JabatanExport($jabatan), 'data-jabatan.xlsx');
    }
}
