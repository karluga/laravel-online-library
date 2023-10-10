@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card p-0">
            <div class="card-header">{{ __('Book Catalog') }}</div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                {{ __('Here you can resereve books to pick up later at the library.') }}
            </div>
        </div>
        <div class="container">
            <h2>Browse books</h2>
            <form action="{{ route('home') }}" method="get">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="search" placeholder="Search for books..." value="{{ $searchQuery }}">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
                            
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="by_author" id="by_author" {{ $byAuthor ? 'checked' : '' }}>
                    <label class="form-check-label" for="by_author">
                        By Author
                    </label>
                </div>
                            
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="show_unavailable" id="show_unavailable" {{ $showUnavailable ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_unavailable">
                        Include unavailable books
                    </label>
                </div>
            </form>
            

            <div class="catalog-cards">
        
                @if ($books->isEmpty())
                <p>No books found for query: {{ request('search') }}</p>
                @else
                    @include('layouts/books', ['books' => $books, 'borrowedBooks' => $borrowedBooks])
                @endif
                
        
                {{-- example --}}
                {{-- <div class="book-card">
                    <img src="cover_image.jpg" alt="Cover Image">
                    <h2>Book Title</h2>
                    <p>Author: Author Name</p>
                    <p>Release Year: Release Year</p>
                    <p>Availability: Number of copies left</p>
                    <p>Rating: ★★★★☆</p>
                    <button type="button">Borrow</button>
                </div> --}}
        
            </div>
        </div>
    </div>
</div>

@include('layouts/modal')

@endsection
