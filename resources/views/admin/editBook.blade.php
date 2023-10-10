@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Book</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form class="row" method="POST" action="{{ route('admin.updateBook', $book->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="col-md-8">
            <div class="form-group required">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $book->title }}" required>
            </div>
    
            <div class="form-group required">
                <label for="author">Author</label>
                <input type="text" class="form-control" id="author" name="author" value="{{ $book->author }}" required>
            </div>
    
            <div class="form-group required">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4">{{ $book->description }}</textarea>
            </div>
    
            <div class="form-group required">
                <label for="release_date">Release Date</label>
                <input type="date" class="form-control" id="release_date" name="release_date" value="{{ $book->release_date }}" required>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control" name="image" id="image" class="form-control"  onchange="previewImage(this);">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update Book</button>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-6">
                    <label>Current Cover Image</label>
                    @if (empty($book->image_path))
                    <!-- Display the placeholder image when $book->image_path is empty -->
                    <img src="/storage/images/placeholders/book-cover-placeholder.png" alt="No Cover Image" width="150">
                    @else
                    <!-- Display the book's image when $book->image_path is not empty -->
                    <img src="{{ Storage::url($book->image_path) }}" alt="Current Cover Image" width="150">
                    @endif  
                </div>
                <div class="col-md-6">
                    <label>Image Preview</label>
                    <img id="image-preview" src="/storage/images/placeholders/book-cover-placeholder.png" alt="Image Preview" width="150">
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        const imagePreview = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            imagePreview.src = '';
        }
    }
</script>
@endsection