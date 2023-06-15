<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homecare extends Model
{
    use HasFactory;

    protected $table = 'homecare';
    protected $fillable = [
        'kode_homecare',
        'name',
        'bayar_id',
        'kategori_id',
        'poli_id',
        'paket_obat',
        'kso',
        'jasa_medis_dokter',
        'jasa_medis_perawat',
        'jasa_rumah_sakit',
        'menejemen',
        'total_biaya_dokter',
        'total_biaya_perawat',
        'total_biaya_perawat_dokter',
    ];

    public function bayar()
    {
        return $this->belongsTo(Bayar::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }
}
