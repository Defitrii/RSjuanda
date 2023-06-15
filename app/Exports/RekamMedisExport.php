<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekamMedisExport implements FromCollection, WithHeadings
{
    protected $rekamMedis;

    public function __construct($rekamMedis)
    {
        $this->rekamMedis = $rekamMedis;
    }

    public function collection()
    {
        return $this->rekamMedis->map(function ($item) {
            return [
                $item->kode_rekam_medis,
                $item->user->name,
                $item->user->no_telepon,
                $item->keluhan,
                $item->diagnosa,
                $item->resep_obat,
                $item->tanggal_kunjungan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Pasien',
            'No Telepon',
            'Keluhan',
            'Diagnosa',
            'Resep Obat',
            'Tanggal Kunjungan',
        ];
    }
}
