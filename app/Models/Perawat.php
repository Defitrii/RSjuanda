<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Perawat extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::uuid4()->toString();
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    protected $table = 'perawat';
    protected $fillable = [
        'user_id',
        'nip',
        'gol_darah',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'status_nikah',
        'jabatan_id',
        'jam_masuk',
        'jam_pulang',
        'hari',
        'deskripsi',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function transaksi()
    {
        return $this->hasOne(TransaksiHomecarePerawat::class);
    }
}
