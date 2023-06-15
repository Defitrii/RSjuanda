<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DokterExport implements FromCollection, WithHeadings
{
    protected $dokter;

    public function __construct($dokter)
    {
        $this->dokter = $dokter;
    }

    public function collection()
    {
        return $this->dokter->map(function ($item) {
            return [
                $item->nip,
                $item->user->name,
                $item->user->email,
                $item->user->no_telepon,
                $item->spesialis,
                $item->status == 0 ? 'Melayani' : 'Tidak Sedang Melayani',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Email',
            'No Telepon',
            'Spesialis',
            'Status',
        ];
    }
}
