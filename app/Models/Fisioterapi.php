<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fisioterapi extends Model
{
    use HasFactory;
    protected $table = 'fisioterapi';
    protected $fillable = [
        'kode_fisioterapi',
        'name',
        'deskripsi',
        'harga'
    ];
}
