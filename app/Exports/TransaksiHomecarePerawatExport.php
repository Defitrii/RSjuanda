<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransaksiHomecarePerawatExport implements FromCollection, WithHeadings
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
                $item->riwayat_penyakit,
                $item->jarak,
                $item->metode_pembayaran,
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
            'Riwayat Penyakit',
            'Jarak',
            'Metode Pembayaran',
            'Biaya Tambahan',
            'Total Biaya',
        ];
    }
}
