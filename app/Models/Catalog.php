<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use HasFactory;

    protected $fillable = ['image_path', 'title', 'description', 'author', 'release_date', 'availability'];

    public function getBookById($id) {
        return Catalog::select('catalogs.*')
            ->leftJoin('borrows', 'catalogs.id', '=', 'borrows.borrowed_book_id')
            ->selectRaw('catalogs.*, 
                (catalogs.availability - COUNT(borrows.id)) as remaining_availability, 
                IFNULL(ROUND(AVG(ratings.rating), 0), 0) as average_rating')
            ->leftJoin('ratings', 'catalogs.id', '=', 'ratings.book_id')
            ->groupBy('catalogs.id')
            ->where('catalogs.id', $id) // Add this condition to filter by book ID
            ->first();
    }
}
