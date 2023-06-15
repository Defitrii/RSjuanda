<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JabatanExport implements FromCollection, WithHeadings
{
    protected $jabatan;

    public function __construct($jabatan)
    {
        $this->jabatan = $jabatan;
    }

    public function collection()
    {
        return $this->jabatan;
    }

    public function headings(): array
    {
        return [
            'Kode Jabatan',
            'Nama Jabatan',
        ];
    }
}
