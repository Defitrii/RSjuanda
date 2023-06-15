<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';
    protected $fillable = ['kode_jabatan', 'name'];

    public function perawat()
    {
        return $this->hasOne(Perawat::class);
    }

    public function dokter()
    {
        return $this->hasOne(Dokter::class);
    }
}
