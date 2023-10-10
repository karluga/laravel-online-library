@extends('layouts.app')

@section('content')
<style>
.rating:hover label:hover input ~ .icon {
  color: red;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <!-- Book Info -->
            <h2>Book Information</h2>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">“{{ $book->title }}”</h5>
                    <p class="card-text">Author: {{ $book->author }}</p>
                    <p class="card-text">Release Date: {{ $book->release_date }}</p>
                    <p class="card-text">Description: {{ $book->description }}</p>
                </div>
            </div>
            <!-- Rating -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Rating</h5>
                    <div class="d-flex flex-column align-items-start">
                        <div class="rating">
                            <label>
                                <input type="radio" name="stars" value="1" disabled {{ $book->average_rating && $book->average_rating == 1 ? 'checked' : '' }} />
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="2" disabled {{ $book->average_rating && $book->average_rating == 2 ? 'checked' : '' }} />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="3" disabled {{ $book->average_rating && $book->average_rating == 3 ? 'checked' : '' }} />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>   
                            </label>
                            <label>
                                <input type="radio" name="stars" value="4" disabled {{ $book->average_rating && $book->average_rating == 4 ? 'checked' : '' }} />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="5" disabled {{ $book->average_rating && $book->average_rating == 5 ? 'checked' : '' }} />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                            </label>                            
                        </div>
                        <a class="link" href="http://localhost:8000/login">Login to rate this book</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex justify-content-start flex-column">
            <!-- Book Cover -->
            <h2>Book Cover</h2>
            <img src="{{ Storage::url($book->image_path) }}" alt="Book Cover" class="img-fluid rounded">
        </div>
    </div>
</div>
@endsection