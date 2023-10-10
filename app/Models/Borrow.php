<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'borrowed_book_id', 'borrowed_book_expiry', 'borrowed_book_returned'];

    public static $rules = [
        'user_id' => 'required|integer',
        'borrowed_book_id' => 'required|integer',
        'borrowed_book_expiry' => 'required|date',
        // 'borrowed_book_returned' => ''
    ];
    public function getActiveBorrowedBooks()
    {
        $userId = auth()->user()->id;

        // Retrieve borrowed books where the user ID matches and borrowed_book_returned isn't empty, null, or 0
        $borrows = Borrow::select('borrows.*', 'catalogs.title as title')
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->where('borrowed_book_returned', '!=', 1)
                    ->orWhereNull('borrowed_book_returned');
            })
            ->join('catalogs', 'borrows.borrowed_book_id', '=', 'catalogs.id')
            ->get();
        // dd($borrows);
        return $borrows;
    }
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function book()
    // {
    //     return $this->belongsTo(Book::class, 'borrowed_book_id');
    // }
    
    // public function isExpired()
    // {
    //     return now()->greaterThan($this->borrowed_book_expiry);
    // }

    // public function getBorrowedBookExpiryAttribute($value)
    // {
    //     return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    // }

    // public function setBorrowedBookExpiryAttribute($value)
    // {
    //     $this->attributes['borrowed_book_expiry'] = \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    // }

}