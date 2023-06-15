<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $fillable = [
        'name',
        'email',
        'no_telepon',
        'password',
        'type',
        'gender',
        'address',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function type(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ["Pasien", "Administrator", "Perawat", "Dokter"][$value],
        );
    }

    public function dokter()
    {
        return $this->hasOne(Dokter::class);
    }

    public function perawat()
    {
        return $this->hasOne(Perawat::class);
    }

    public function pasien()
    {
        return $this->hasOne(Pasien::class);
    }

    public function chatpayments()
    {
        return $this->hasMany(Chatpayment::class);
    }
}
