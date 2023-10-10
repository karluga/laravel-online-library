<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrow;
use App\Models\Catalog;


class BorrowController extends Controller
{

    public function borrowBook(Request $request)
    {
        // Validate the request data
        $request->validate([
            'book_id' => 'required|integer',
            'borrow_interval' => 'required|integer',
        ]);
    
        // Get the book ID from the request
        $bookId = $request->input('book_id');
    
        // Check if the book is in stock
        $book = Catalog::find($bookId);
    
        if (!$book) {
            $errors = ['doesnt_exist' => 'Book not found.'];
            return back()->withErrors($errors);
        }
    
        // Check if the book can be borrowed
        if ($book->availability > 0) {
            // Check if there is a book with the same ID and the 'borrowed_book_returned' value is empty
            $existingBorrow = Borrow::where('user_id', auth()->user()->id)
                ->where('borrowed_book_id', $bookId)
                ->where(function($query) {
                    $query->where('borrowed_book_returned', false)
                        ->orWhereNull('borrowed_book_returned');
                })
                ->first();
            if ($existingBorrow) {
                $errors = ['already_borrowed' => 'This book has already been borrowed and not returned.'];
                return back()->withErrors($errors);
            }
    
            // Get the number of days specified from the input
            $days = (int) $request->input('borrow_interval');
    
            // Calculate the timestamp by adding the days to the current time and round up to the nearest whole day
            $timestamp = ceil((time() + ($days * 24 * 60 * 60)) / (24 * 60 * 60)) * (24 * 60 * 60);

            // Convert the timestamp to a datetime format
            $borrowedBookExpiry = date('Y-m-d H:i:s', $timestamp);
    
            // Create a new Borrow record
            Borrow::create([
                'user_id' => auth()->user()->id,
                'borrowed_book_id' => $bookId,
                'borrowed_book_expiry' => $borrowedBookExpiry,
            ]);
    
            // Redirect back with a success message
            return back()->with('success', 'Book borrowed successfully. Please pick it up soon!');
        } else {
            $errors = ['not_available' => 'Sorry, the book is not available for borrowing at the moment.'];
            return back()->withErrors($errors);
        }
    }
    
    
    public function returnBook(Request $request)
    {
        // Validate the request data
        $request->validate([
            'book_id' => 'required|integer',
        ]);

        // Get the book ID from the request
        $bookId = $request->input('book_id');
        // dd($bookId);

        // Find the borrowed book record
        $borrowedBook = Borrow::where('user_id', auth()->user()->id)
            ->where('borrowed_book_id', $bookId)
            ->where(function($query) {
                $query->where('borrowed_book_returned', false)
                    ->orWhereNull('borrowed_book_returned');
            })
            ->first();
        if (!$borrowedBook) {
            $errors = ['not_found' => 'Book not found in your borrowed books.'];
            return back()->withErrors($errors);
        }

        // Update the borrowed book record to mark it as returned
        $borrowedBook->update([
            'borrowed_book_returned' => now(),
        ]);

        // Redirect back with a success message
        return back()->with('success', 'Book returned successfully.');
    }
}