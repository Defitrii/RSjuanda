<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransaksiFisioterapiExport implements FromCollection, WithHeadings
{
    protected $transaksiFisioterapi;

    public function __construct($transaksiFisioterapi)
    {
        $this->transaksiFisioterapi = $transaksiFisioterapi;
    }

    public function collection()
    {
        return $this->transaksiFisioterapi->map(function ($item) {
            return [
                $item->pasien->name,
                $item->perawat->name,
                $item->dokter->name,
                $item->fisioterapi->name,
                $item->riwayat_penyakit,
                $item->jarak,
                $item->metode_pembayaran,
                $item->fisioterapi->harga,
                $item->biaya_tambahan,
                $item->total_biaya,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Pasien',
            'Perawat',
            'Dokter',
            'Fisioterapi',
            'Riwayat Penyakit',
            'Jarak',
            'Metode Pembayaran',
            'Biaya Fisioterapi',
            'Biaya Tambahan',
            'Total Biaya',
        ];
    }
}
