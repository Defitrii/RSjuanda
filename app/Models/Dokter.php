<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Dokter extends Model
{
    use HasFactory;
    protected $table = 'dokter';

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

    protected $fillable = [
        'user_id',
        'spesialis',
        'pengalaman_tahun',
        'deskripsi',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function chatpayments()
    {
        return $this->hasMany(Chatpayment::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
