<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PasienExport implements FromCollection, WithHeadings
{
    protected $pasien;

    public function __construct($pasien)
    {
        $this->pasien = $pasien;
    }

    public function collection()
    {
        return $this->pasien;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'No Telepon',
            'Alamat',
        ];
    }
}
