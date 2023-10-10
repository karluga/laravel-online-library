@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in as admin, so you have the privilleges to create, edit and delete books from the catalog.') }}
                </div>
            </div>

            {{-- MY STUFF --}}
            <div class="container">
                <h2>New book entry</h2>
                <form action="{{ route('admin.createBook') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group required">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" required value="{{ old('title') }}">
                        @error('title')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group required">
                        <label for="description">Description</label>
                        <textarea type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group required">
                        <label for="author">Author</label>
                        <input type="text" name="author" id="author" class="form-control @error('author') is-invalid @enderror" required value="{{ old('author') }}">
                        @error('author')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                        @error('image')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group required">
                        <label for="date">Release date</label>
                        <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" required>
                        @error('date')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Upload</button>
                </form>
                <hr>
                
                <h2>Browse book catalog</h2>
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteIcons = document.querySelectorAll('.delete-icon');
        
        deleteIcons.forEach(icon => {
            icon.addEventListener('click', function(event) {
                event.preventDefault();
                const bookId = this.getAttribute('data-book-id');
                const confirmation = confirm('Are you sure you want to delete this book?');
                
                if (confirmation) {
                    window.location.href = `/admin/deleteBook/${bookId}`;
                }
            });
        });
    });
</script>

@include('layouts/modal')

@endsection