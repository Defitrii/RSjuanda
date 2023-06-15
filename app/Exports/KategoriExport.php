<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KategoriExport implements FromCollection, WithHeadings
{
    protected $kategori;

    public function __construct($kategori)
    {
        $this->kategori = $kategori;
    }

    public function collection()
    {
        return $this->kategori;
    }

    public function headings(): array
    {
        return [
            'Kode Kategori',
            'Nama Kategori',
        ];
    }
}
