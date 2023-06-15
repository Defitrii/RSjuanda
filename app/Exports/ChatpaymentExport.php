<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ChatpaymentExport implements FromCollection, WithHeadings
{
    protected $chatpayment;

    public function __construct($chatpayment)
    {
        $this->chatpayment = $chatpayment;
    }

    public function collection()
    {
        return $this->chatpayment->map(function ($item) {
            return [
                $item->user->name,
                $item->user->no_telepon,
                $item->dokter->user->name,
                $item->waktu_mulai,
                $item->waktu_selesai,
                $item->biaya_chat,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Pasien',
            'No Telepon',
            'Dokter',
            'Waktu Mulai',
            'Waktu Selesai',
            'Biaya Chat',
        ];
    }
}
