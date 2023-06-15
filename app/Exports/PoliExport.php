<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PoliExport implements FromCollection, WithHeadings
{
    protected $poli;

    public function __construct($poli)
    {
        $this->poli = $poli;
    }

    public function collection()
    {
        return $this->poli;
    }

    public function headings(): array
    {
        return [
            'Kode Poli',
            'Nama Poli',
        ];
    }
}
