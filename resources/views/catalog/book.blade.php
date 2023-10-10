@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
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
            <div class="mb-3 d-flex flex-row">
                <div class="col card">
                    <div class="card-body">
                        @if ($userRating)
                        <h5 class="card-title">My Rating</h5>
                        @else
                        <h5 class="card-title">Rate This Book</h5>
                        @endif                    
                        <form class="d-flex flex-column align-items-start" action="{{ url()->current() }}" method="POST">
                            @csrf
                            <div class="rating">
                                <label>
                                    <input type="radio" name="stars" value="1" {{ $userRating && $userRating->rating == 1 ? 'checked' : '' }} />
                                    <span class="icon">★</span>
                                </label>
                                <label>
                                    <input type="radio" name="stars" value="2" {{ $userRating && $userRating->rating == 2 ? 'checked' : '' }} />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                </label>
                                <label>
                                    <input type="radio" name="stars" value="3" {{ $userRating && $userRating->rating == 3 ? 'checked' : '' }} />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>   
                                </label>
                                <label>
                                    <input type="radio" name="stars" value="4" {{ $userRating && $userRating->rating == 4 ? 'checked' : '' }} />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                </label>
                                <label>
                                    <input type="radio" name="stars" value="5" {{ $userRating && $userRating->rating == 5 ? 'checked' : '' }} />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                </label>                            
                            </div>
                            <button type="submit" class="btn btn-primary borrow-btn mt-3">Publish</button>
                        </form>
                    </div>
                </div>

                @if ($borrowedBooks->where('borrowed_book_id', $book->id)->count() > 0)
                <div class="col card">
                    <div class="card-body">
                        <p class="text-success">In Possession</p>
                        <form class="returnForm" action="/home/returnBook" method="POST" onsubmit="return confirmReturn();">
                            @csrf
                            <input type="hidden" value="{{ $book->id }}" name="book_id">
                            <button type="submit" class="btn btn-danger return-btn">Return</button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @if(!empty($book->image_path))
        <div class="col-md-4 d-flex justify-content-start flex-column">
            <!-- Book Cover -->
            <h2>Book Cover</h2>
            <img src="{{ Storage::url($book->image_path) }}" alt="Book Cover" class="img-fluid rounded">
        </div>
        @endif
    </div>
</div>
<script>
$(':radio').change(function() {
  console.log('New star rating: ' + this.value);
});
</script>
@endsection