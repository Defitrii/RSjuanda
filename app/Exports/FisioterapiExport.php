<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FisioterapiExport implements FromCollection, WithHeadings
{
    protected $fisioterapi;

    public function __construct($fisioterapi)
    {
        $this->fisioterapi = $fisioterapi;
    }

    public function collection()
    {
        return $this->fisioterapi;
    }

    public function headings(): array
    {
        return [
            'Kode Fisioterapi',
            'Nama Fisioterapi',
            'Harga',
        ];
    }
}
