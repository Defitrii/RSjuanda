<?php

namespace App\Http\Controllers\backend;

use App\Exports\LaporanTransaksiFisioterapiExport;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\TransaksiFisioterapi;
use App\Models\Village;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class LaporanTransaksiFisioeterapiController extends Controller
{
    public function waktu(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->start_date)) {
                $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->whereBetween('created_at', array($request->start_date, $request->end_date))
                    ->get();
            } else {
                $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->get();
            }
            return DataTables::of($transaksiFisioterapi)
                ->addIndexColumn()
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
                ->addColumn('total_biaya', function ($data) {
                    $total_biaya = "Rp. " . number_format($data->total_biaya, 0, ',', '.');
                    return $total_biaya;
                })
                ->make(true);
        }
        return view('backend.transaksiFisioterapi.laporan.filter_waktu');
    }

    public function printPDF(Request $request)
    {
        if (!empty($request->start_date)) {
            $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->whereBetween('created_at', array($request->start_date, $request->end_date))
                ->get();
        } else {
            $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->get();
        }
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $pdf = Pdf::loadView('backend.transaksiFisioterapi.laporan.printPDF', compact('transaksiFisioterapi', 'start_date', 'end_date'));
        return $pdf->download('laporan-transaksi-fisioterapi-' . time() . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        // ambil nilai start date dan end date dari request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // query data dari database dengan menggunakan nilai start date dan end date
        if (!empty($request->start_date)) {
            $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->get();
        }

        // download file excel dengan data yang telah di-query
        return Excel::download(new LaporanTransaksiFisioterapiExport($transaksiFisioterapi), 'laporan-transaksi-fisioterapi.xlsx');
    }

    public function wilayah(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->provinsi)) {
                $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')
                    ->when($request->provinsi, function ($query) use ($request) {
                        return $query->where('provinsi_id', $request->provinsi);
                    })
                    ->when($request->kabupaten, function ($query) use ($request) {
                        return $query->where('kabupaten_id', $request->kabupaten);
                    })
                    ->when($request->kecamatan, function ($query) use ($request) {
                        return $query->where('kecamatan_id', $request->kecamatan);
                    })
                    ->when($request->desa, function ($query) use ($request) {
                        return $query->where('desa_id', $request->desa);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();
            } else {
                $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->get();
            }
            return DataTables::of($transaksiFisioterapi)
                ->addIndexColumn()
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
                ->addColumn('total_biaya', function ($data) {
                    $total_biaya = "Rp. " . number_format($data->total_biaya, 0, ',', '.');
                    return $total_biaya;
                })
                ->make(true);
        }
        $provinces = Province::all();
        return view('backend.transaksiFisioterapi.laporan.filter_wilayah', compact('provinces'));
    }

    public function getKabupaten(Request $request)
    {
        $id_provinsi = $request->id_provinsi;
        $kabupaten = Regency::where('province_id', $id_provinsi)->get();
        foreach ($kabupaten as $row) {
            echo "<option value='" . $row->id . "'>" . $row->name . "</option>";
        }
    }

    public function getKecamatan(Request $request)
    {
        $id_kabupaten = $request->id_kabupaten;
        $kecamatan = District::where('regency_id', $id_kabupaten)->get();
        foreach ($kecamatan as $row) {
            echo "<option value='" . $row->id . "'>" . $row->name . "</option>";
        }
    }

    public function getDesa(Request $request)
    {
        $id_kecamatan = $request->id_kecamatan;
        $desa = Village::where('district_id', $id_kecamatan)->get();
        foreach ($desa as $row) {
            echo "<option value='" . $row->id . "'>" . $row->name . "</option>";
        }
    }

    public function printPDFWilayah(Request $request)
    {
        if (!empty($request->provinsi)) {
            $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')
                ->when($request->provinsi, function ($query) use ($request) {
                    return $query->where('provinsi_id', $request->provinsi);
                })
                ->when($request->kabupaten, function ($query) use ($request) {
                    return $query->where('kabupaten_id', $request->kabupaten);
                })
                ->when($request->kecamatan, function ($query) use ($request) {
                    return $query->where('kecamatan_id', $request->kecamatan);
                })
                ->when($request->desa, function ($query) use ($request) {
                    return $query->where('desa_id', $request->desa);
                })
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->get();
        }

        $provinsi = Province::find($request->provinsi);
        $kabupaten = Regency::find($request->kabupaten);
        $kecamatan = District::find($request->kecamatan);
        $desa = Village::find($request->desa);

        $pdf = Pdf::loadView('backend.transaksiFisioterapi.laporan.printPDFWilayah', compact('transaksiFisioterapi', 'provinsi', 'kabupaten', 'kecamatan', 'desa'));
        return $pdf->download('laporan-transaksi-fisioterapi-' . time() . '.pdf');
    }

    public function exportExcelWilayah(Request $request)
    {
        // ambil nilai start date dan end date dari request
        if (!empty($request->provinsi)) {
            $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')
                ->when($request->provinsi, function ($query) use ($request) {
                    return $query->where('provinsi_id', $request->provinsi);
                })
                ->when($request->kabupaten, function ($query) use ($request) {
                    return $query->where('kabupaten_id', $request->kabupaten);
                })
                ->when($request->kecamatan, function ($query) use ($request) {
                    return $query->where('kecamatan_id', $request->kecamatan);
                })
                ->when($request->desa, function ($query) use ($request) {
                    return $query->where('desa_id', $request->desa);
                })
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            $transaksiFisioterapi = TransaksiFisioterapi::with('pasien', 'perawat', 'dokter', 'fisioterapi')->get();
        }

        // download file excel dengan data yang telah di-query
        return Excel::download(new LaporanTransaksiFisioterapiExport($transaksiFisioterapi), 'laporan-transaksi-fisioterapi.xlsx');
    }
}
