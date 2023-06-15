<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bayar extends Model
{
    use HasFactory;

    protected $table = 'bayar';
    protected $fillable = ['kode_bayar', 'name'];

    public function homecare()
    {
        return $this->hasOne(Homecare::class);
    }
}
