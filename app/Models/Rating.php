<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'rating',
    ];

    public function existingRating($id, $userId) {

        return Rating::where('book_id', $id)
            ->where('user_id', $userId)
            ->first();
    }
    
}