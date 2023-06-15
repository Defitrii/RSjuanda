<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiHomecarePerawat extends Model
{
    use HasFactory;
    protected $table = 'transaksi_homecare_perawat';
    protected $fillable = [
        'pasien_id',
        'perawat_id',
        'homecare',
        'riwayat_penyakit',
        'waktu',
        'waktu_selesai',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',
        'jarak',
        'metode_pembayaran',
        'bukti_pembayaran',
        'biaya_tambahan',
        'total_biaya',
        'deskripsi_kegiatan',
        'status',
    ];

    public function pasien()
    {
        return $this->belongsTo(User::class, 'pasien_id');
    }

    public function perawat()
    {
        return $this->belongsTo(User::class, 'perawat_id');
    }
}
