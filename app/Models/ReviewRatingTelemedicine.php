<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewRatingTelemedicine extends Model
{
    use HasFactory;

    protected $table = 'review_ratings_telemedicine';
    protected $fillable = [
        'dokter_id',
        'user_id',
        'rating',
        'komen',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
