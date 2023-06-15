<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Pasien extends Model
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

    protected $table = 'pasien';
    protected $fillable = [
        'user_id',
        'gol_darah',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'pekerjaan',
        'status_nikah',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
