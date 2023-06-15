<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BayarExport implements FromCollection, WithHeadings
{
    protected $bayar;

    public function __construct($bayar)
    {
        $this->bayar = $bayar;
    }

    public function collection()
    {
        return $this->bayar;
    }

    public function headings(): array
    {
        return [
            'Kode Bayar',
            'Nama Bayar',
        ];
    }
}
