<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\Borrow;
use App\Models\Rating;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'book', 'randomBook']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
     public function index(Request $request)
     {
         $searchQuery = $request->input('search');
         $byAuthor = $request->has('by_author');
         $showUnavailable = $request->has('show_unavailable');
     
         $booksQuery = Catalog::select('catalogs.*')
             ->leftJoin('borrows', 'catalogs.id', '=', 'borrows.borrowed_book_id')
             ->selectRaw('catalogs.*, 
                 (catalogs.availability - COUNT(borrows.id)) as remaining_availability, 
                 IFNULL(ROUND(AVG(ratings.rating), 0), 0) as average_rating')
             ->leftJoin('ratings', 'catalogs.id', '=', 'ratings.book_id')
             ->groupBy('catalogs.id');     
         if ($searchQuery) {
             $booksQuery->where(function ($query) use ($searchQuery, $byAuthor) {
                 $query->where('title', 'like', '%' . $searchQuery . '%');
                 if ($byAuthor) {
                     $query->orWhere('author', 'like', '%' . $searchQuery . '%');
                 }
             });
         }
         if (!$showUnavailable) {
             $booksQuery->havingRaw('(catalogs.availability - COUNT(borrows.id)) > 0');
         }
     
         $books = $booksQuery->get();
        // Use in the blade.php file:
            // {{ $book->id }}
            // {{ $book->title }}
            // {{ $book->image_path }}
            // {{ $book->release_date }}
            // + not in the table
            // {{ $book->remaining_availability }}
            // {{ $book->average_rating }}

        // Check if the user is authenticated
        if (auth()->check()) {

            $borrowModel = new Borrow();
            $borrowedBooks = $borrowModel->getActiveBorrowedBooks();

            // Pass the borrowedBooks data to the 'catalog.home' view
            return view('catalog.home', compact('books', 'searchQuery', 'byAuthor', 'showUnavailable', 'borrowedBooks'));
        } else {
            // If the user is not authenticated, return a different view
            return view('welcome', compact('books', 'searchQuery', 'byAuthor', 'showUnavailable'));
        }
    }
    public function book($id)
    {
        $CatalogModel = new Catalog();
        $book = $CatalogModel->getBookById($id);
        
        // You can add error handling if the book is not found, for example:
        if (!$book) {
            abort(404); // Show a 404 error if the book is not found.
        }
    
        // Get the currently authenticated user.
        $user = \Auth::user();
    
        // Check if the user is authenticated
        if ($user) {
            $rating = new Rating();
    
            $userRating = $rating->existingRating($id, $user->id);
    
            $borrowModel = new Borrow();
            $borrowedBooks = $borrowModel->getActiveBorrowedBooks();
            // Pass both book and userRating data to the view
            return view('catalog.book', compact('book', 'userRating', 'borrowedBooks'));
        } else {
            // If the user is not authenticated, return a different view
            return view('book', compact('book'));
        }
    }
    public function randomBook(Request $request)
    {
        // Reference query to get random available books
        $booksQuery = Catalog::select('catalogs.*')
            ->leftJoin('borrows', 'catalogs.id', '=', 'borrows.borrowed_book_id')
            ->selectRaw('catalogs.*, 
                (catalogs.availability - COUNT(borrows.id)) as remaining_availability, 
                IFNULL(ROUND(AVG(ratings.rating), 0), 0) as average_rating')
            ->leftJoin('ratings', 'catalogs.id', '=', 'ratings.book_id')
            ->groupBy('catalogs.id')
            ->havingRaw('(catalogs.availability - COUNT(borrows.id)) > 0')
            ->inRandomOrder() // Order the results randomly
            ->first(); // Get the first random available book
    
            if ($booksQuery) {
                // Redirect to the route for a specific book with the ID of the random available book
                return redirect()->route('home.book', ['id' => $booksQuery->id]);
            } else {
                // Handle the case when no random available book is found
                return redirect('home.book')->with('error', 'No random available book found.');
            }
    }
    public function rate(Request $request, $id) {
        // Validate the form data (in this case, we'll just validate that 'stars' is a number between 1 and 5).
        $request->validate([
            'stars' => 'required|integer|min:1|max:5',
        ]);
    
        $user = \Auth::user();
        $rating = new Rating();

        $existingRating = $rating->existingRating($id, $user->id);
        // If a rating exists, update it; otherwise, create a new one.
        // dd($existingRating);
        $existingRating = $rating->existingRating($id, $user->id);
        // If a rating exists and it's not empty, update it; otherwise, create a new one.
        if ($existingRating && !empty($existingRating->rating)) {
            if (intval($request->input('stars')) == $existingRating->rating) {
                return redirect()->route('home.book', ['id' => $id])
                    ->withErrors(['rating' => 'You haven\'t changed your rating. It remains the same.']);
            } else {
                $existingRating->update([
                    'rating' => $request->input('stars'),
                ]);
        
                return redirect()->route('home.book', ['id' => $id])->with('success', 'Rating updated successfully');
            }
        } else {
            $rating->book_id = $id;
            $rating->user_id = $user->id;
            $rating->rating = $request->input('stars');
            $rating->save();
        
            return redirect()->route('home.book', ['id' => $id])->with('success', 'Rating submitted successfully');
        }
        
    
    }
}