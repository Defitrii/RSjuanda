<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HomecareExport implements FromCollection, WithHeadings
{
    protected $homecare;

    public function __construct($homecare)
    {
        $this->homecare = $homecare;
    }

    public function collection()
    {
        return $this->homecare->map(function ($item) {
            return [
                $item->kode_homecare,
                $item->name,
                $item->bayar->name,
                $item->kategori->name,
                $item->poli->name,
                $item->paket_obat,
                $item->kso,
                $item->jasa_medis_dokter,
                $item->jasa_medis_perawat,
                $item->jasa_rumah_sakit,
                $item->menejemen,
                $item->total_biaya_dokter,
                $item->total_biaya_perawat,
                $item->total_biaya_perawat_dokter,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode Homecare',
            'Nama',
            'Bayar',
            'Kategori',
            'Poli',
            'Paket Obat',
            'KSO',
            'Jasa Medis Dokter',
            'Jasa Medis Perawat',
            'Jasa Rumah Sakit',
            'Manajemen',
            'Total Biaya Dokter',
            'Total Biaya Perawat',
            'Total Biaya Perawat & Dokter',
        ];
    }
}
