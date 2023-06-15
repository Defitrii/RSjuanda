<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransaksiHomecareExport implements FromCollection, WithHeadings
{
    protected $transaksiHomecare;

    public function __construct($transaksiHomecare)
    {
        $this->transaksiHomecare = $transaksiHomecare;
    }

    public function collection()
    {
        return $this->transaksiHomecare->map(function ($item) {
            return [
                $item->pasien->name,
                $item->perawat->name,
                $item->dokter->name,
                $item->homecare->name,
                $item->riwayat_penyakit,
                $item->jarak,
                $item->metode_pembayaran,
                $item->homecare->total_biaya_perawat_dokter,
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
            'Homecare',
            'Riwayat Penyakit',
            'Jarak',
            'Metode Pembayaran',
            'Biaya Homecare',
            'Biaya Tambahan',
            'Total Biaya',
        ];
    }
}
