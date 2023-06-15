<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LayananExport implements FromCollection, WithHeadings
{
    protected $layanan;

    public function __construct($layanan)
    {
        $this->layanan = $layanan;
    }

    public function collection()
    {
        return $this->layanan;
    }

    public function headings(): array
    {
        return [
            'Kode Layanan',
            'Nama Layanan',
            'Harga',
        ];
    }
}
