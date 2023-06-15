<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiFisioterapi extends Model
{
    use HasFactory;

    protected $table = 'transaksi_fisioterapi';
    protected $fillable = [
        'user_id',
        'perawat_id',
        'dokter_id',
        'fisioterapi_id',
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
        'status'
    ];

    public function pasien()
    {
        return $this->belongsTo(User::class, 'pasien_id');
    }

    public function perawat()
    {
        return $this->belongsTo(User::class, 'perawat_id');
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function fisioterapi()
    {
        return $this->belongsTo(Fisioterapi::class, 'fisioterapi_id');
    }
}
