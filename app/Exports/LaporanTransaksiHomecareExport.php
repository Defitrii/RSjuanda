<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanTransaksiHomecareExport implements FromCollection, WithHeadings
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
                $item->jarak,
                $item->waktu,
                $item->metode_pembayaran,
                $item->total_biaya,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Pasien',
            'Perawat',
            'Jarak',
            'Waktu',
            'Metode Pembayaran',
            'Total Biaya',
        ];
    }
}
