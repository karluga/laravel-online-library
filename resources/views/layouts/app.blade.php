<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">{{-- from /public --}}

    <!-- Scripts -->
    <!-- BOOTSTRAP LIBRARY WITH JQUERY -->
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    {{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    {{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script></head>

</head>
<body>
    <div id="app">
        <div class="navbar-wrapper">

            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container py-2">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <div class="logo-container">
                        @svg('logo-with-text.svg', 'bookme-logo')
                    </div>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        {{-- @if(Auth::check() && Auth::user()->role == 1)
                        <li class="nav-item">
                            <a style="pointer-events: none;" class="nav-link" href="/admin/borrowed">{{ __('Borrowed Books') }}</a>
                        </li>
                        @endif --}}
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/home/random">
                                <i class="fa-solid fa-face-smile"></i>
                                {{ __('Feeling Lucky!') }}
                            </a>
                        </li>
                        <!-- Borrowed books dropdown slide reveal -->
                        @if(Auth::check() && isset($borrowedBooks) && $borrowedBooks->isNotEmpty()) {{-- Because objects are never empty --}}

                        <li class="nav-item">
                            <a class="nav-link" id="open-menu-button" href="#">
                                <i class="fa-solid fa-book-bookmark"></i>
                                {{ __('Borrowed Books') }}
                            </a>
                        </li>
                        @endif
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fa-solid fa-user"></i>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @if(isset($borrowedBooks) && $borrowedBooks->isNotEmpty())
        <div class="slide-down-menu">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th class="vertical-title" rowspan="{{ count($borrowedBooks) + 2 }}">{{ __('Borrowed Books') }}</th>
                    </tr>
                    <tr>
                        <th>Title</th>
                        <th>Expiry Date</th>
                        <th>Time Left</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($borrowedBooks as $borrowedBook)
                        <tr>
                            <td>
                                <a href="/home/{{ $borrowedBook->borrowed_book_id }}">{{ $borrowedBook->title }}
                            </td>
                            <td>
                                <span title="{{ \Carbon\Carbon::parse($borrowedBook->borrowed_book_expiry)->toDateString() }}">
                                    {{ \Carbon\Carbon::parse($borrowedBook->borrowed_book_expiry)->format('jS \of F') }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($borrowedBook->borrowed_book_expiry)->diffForHumans() }}</td>
                            <td>
                                <form class="returnForm" action="/home/returnBook" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $borrowedBook->borrowed_book_id }}" name="book_id">
                                    <button type="submit" class="btn btn-danger return-btn">Return</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <script>
            $(document).ready(function() {
                var isAnimating = false;

                // Hide the slide-down-menu initially
                $('.slide-down-menu').hide();

                // Toggle the slide-down-menu when #open-menu-button is clicked
                $('#open-menu-button').click(function() {
                    if (!isAnimating) {
                        isAnimating = true;
                        $('.slide-down-menu').slideToggle(200, function() {
                            // Animation is complete
                            isAnimating = false;
                        });
                    }
                });
            });
            </script>
        @endif
        </div>

        <main class="py-4">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
    
            {{-- this checks if the book upload was successful --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
