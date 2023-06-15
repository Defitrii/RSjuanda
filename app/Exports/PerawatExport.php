<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PerawatExport implements FromCollection, WithHeadings
{
    protected $perawat;

    public function __construct($perawat)
    {
        $this->perawat = $perawat;
    }

    public function collection()
    {
        return $this->perawat->map(function ($item) {
            return [
                $item->nip,
                $item->user->name,
                $item->user->email,
                $item->user->no_telepon,
                $item->jabatan->name,
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
            'Jabatan',
            'Status',
        ];
    }
}
