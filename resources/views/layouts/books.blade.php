@foreach($books as $book)
    <div class="book-card">
        <div class="image-container">
            @if (empty($book->image_path))
            <!-- Display the placeholder image when $book->image_path is empty -->
            <img src="/storage/images/placeholders/book-cover-placeholder.png" alt="No Cover Image">
            @else
            <!-- Display the book's image when $book->image_path is not empty -->
            <img src="{{ Storage::url($book->image_path) }}" alt="Cover Image">
            @endif            
            @if(Auth::check() && Auth::user()->role == 1)
            <a class="edit-icon" href="/admin/editBook/{{ $book->id }}">
                <i class="fas fa-pencil-alt"></i> <!-- Font Awesome edit icon -->
            </a>
            <form method="POST" action="{{ route('admin.deleteBook', $book->id) }}" onsubmit="return confirmDelete();" style="display: inline;">
                @csrf
                @method('DELETE')
                
                <button type="submit" class="delete-icon">
                    <i class="fas fa-trash-alt"></i> <!-- Font Awesome trash icon -->
                </button>
            </form>
            @endif
        </div>
        <h2 class="card-title">
            <a href="/home/{{ $book->id }}">{{ $book->title }}
                <sup style="font-size: 15px"><i class="fa-solid fa-arrow-up-right-from-square"></i></sup>
            </a>
        </h2>
        <p>Author: {{ $book->author }}</p>
        <p>Release Year: {{ date('Y', strtotime($book->release_date)) }}</p>
        <p>
            Availability: 
            @if($book->availability == 0)
                <span class="fw-bold">Not in stock</span>
            @else
                <span class="fw-bold">{{ $book->availability }}/</span>
                <span class="{{ $book->remaining_availability <= 5 ? 'text-danger' : ($book->remaining_availability <= 10 ? 'text-warning' : 'text-success') }} fw-bold">
                    {{ $book->remaining_availability }} {{ $book->remaining_availability != 1 ? 'copies' : 'copy' }} left
                </span>
            @endif
        </p>
        <p>Rating: 
            @if(!empty($book->average_rating)) {{-- ! ( 0; null; isset() ) --}}
                @if(is_numeric($book->average_rating) && $book->average_rating > 0 && $book->average_rating <= 5)
                    @php
                        $fullStars = floor($book->average_rating);
                        $emptyStars = 5 - $fullStars;
                    @endphp
                    @for($i = 0; $i < $fullStars; $i++)
                        ★
                    @endfor
                    @for($i = 0; $i < $emptyStars; $i++)
                        ☆
                    @endfor
                @else
                    ??
                @endif
            @else
                N/A
            @endif
        </p>
        @if(Auth::check())
            {{-- Check if the book's ID is in $borrowedBooks --}}
            @if ($borrowedBooks->where('borrowed_book_id', $book->id)->count() > 0)
            <p class="text-success">In Possession</p>
            <form class="returnForm" action="/home/returnBook" method="POST" onsubmit="return confirmReturn();">
                @csrf
                <input type="hidden" value="{{ $book->id }}" name="book_id">
                <button type="submit" class="btn btn-danger return-btn">Return</button>
            </form>
            @else
            <button type="button" class="btn btn-primary borrow-btn open-borrow-modal" data-book-id="{{ $book->id }}" {{ $book->remaining_availability > 0 ? '' : 'disabled' }}>Borrow</button>
            @endif
        @endif
    </div>
@endforeach
<script>
    function confirmReturn() {
        return confirm("Are you sure you want to return this book?");
    }
    function confirmDelete() {
        return confirm("Are you sure you want to delete this book?");
    }
</script>