<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use App\Models\Catalog;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // for the blade view
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
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

        $borrowModel = new Borrow();
        $borrowedBooks = $borrowModel->getActiveBorrowedBooks();

       // Use in the blade.php file:
           // {{ $book->id }}
           // {{ $book->title }}
           // {{ $book->image_path }}
           // {{ $book->release_date }}
           // + not in the table
           // {{ $book->remaining_availability }}
           // {{ $book->average_rating }}

        return view('admin.dashboard', compact('books', 'searchQuery', 'byAuthor', 'showUnavailable', 'borrowedBooks'));
    }
    public function createBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'author' => 'required',
            'image' => 'image|max:2048',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // dd(strtotime($request->get('date')), $request->get('date'));
        $imagePath = $request->file('image') ? $request->file('image')->store('public/images') : null;

        $book = new Catalog([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'author' => $request->get('author'),
            'image_path' => $imagePath,
            'release_date' => $request->get('date'),
        ]);
        $book->save();
    
        return back()->with('success', 'Book uploaded successfully');
    }
    public function updateBook(Request $request, $id)
    {
        // Find the book to edit by its ID
        $book = Catalog::find($id);

        if (!$book) {
            $errors = ['not_found' => 'Book not found'];
            return back()->withErrors($errors);
        }
    
        // Validate the request
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'description' => 'required',
            'release_date' => 'required|date',
            'image' => 'image|max:2048', // Allow image update but not required
        ]);
    

        // Update the book's information
        $book->title = $request->input('title');
        $book->author = $request->input('author');
        $book->description = $request->input('description');
        $book->release_date = $request->input('release_date');

        // Update the book's image if it's provided in the request
        if ($request->hasFile('image')) {
            // Validate and store the new image
            $imagePath = $request->file('image')->store('public/images');
            $book->image_path = $imagePath;
        }
    
        // Save the updated book information
        $book->save();
    
        return back()->with('success', 'Book information updated successfully');
    }
    
    public function editBook($id)
    {
        // Find the book by its ID
        $book = Catalog::find($id);
    
        if (!$book) {
            return redirect()->back()->with('error', 'Book not found');
        }
        
        return view('admin.editBook', compact('book'));
    }    
    public function deleteBook($id)
    {
        // Find the book by its ID
        $book = Catalog::find($id);
    
        if (!$book) {
            return back()->with('error', 'Book not found');
        }
    
        // Check if anyone has borrowed this book
        $borrowedCount = Borrow::where('borrowed_book_id', $id)->count();
    
        if ($borrowedCount > 0) {
            return redirect()->back()->withErrors(['delete' => 'Cannot delete the book. It has active borrowers.']);

        }
    
        // Delete the book
        $book->delete();
    
        return back()->with('success', 'Book deleted successfully');
    }
}