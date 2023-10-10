@extends('layouts.app')

@section('content')

    <div class="catalog-cards">
        @include('layouts/books', ['books' => $books])
    </div>

@endsection