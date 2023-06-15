<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewRatingHomecare extends Model
{
    use HasFactory;

    protected $table = 'review_rating_homecare';
    protected $fillable = [
        'perawat_id',
        'user_id',
        'rating',
        'komen',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
