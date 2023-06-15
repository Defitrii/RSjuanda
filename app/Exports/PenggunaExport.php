<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenggunaExport implements FromCollection, WithHeadings
{
    protected $pengguna;

    public function __construct($pengguna)
    {
        $this->pengguna = $pengguna;
    }

    public function collection()
    {
        return $this->pengguna->map(function ($item) {
            return [
                $item->name,
                $item->email,
                $item->no_telepon,
                $item->gender == 'L' ? 'Laki-laki' : 'Perempuan',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'No Telepon',
            'Jenis Kelamin',
        ];
    }
}
