<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CatalogController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('welcome');

Auth::routes();

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    Route::post('/createBook', [AdminController::class, 'createBook'])->name('admin.createBook');
    Route::delete('/deleteBook/{id}', [AdminController::class, 'deleteBook'])->name('admin.deleteBook');
    Route::get('/editBook/{id}', [AdminController::class, 'editBook'])->name('admin.editBook');
    Route::put('/updateBook/{id}', [AdminController::class, 'updateBook'])->name('admin.updateBook');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/home/borrowBook', [App\Http\Controllers\BorrowController::class, 'borrowBook'])->name('home.borrowBook');
Route::post('/home/returnBook', [App\Http\Controllers\BorrowController::class, 'returnBook'])->name('home.returnBook');
Route::get('/home/random', [App\Http\Controllers\HomeController::class, 'randomBook'])->name('home.randomBook'); // Wildcards need to be after any other route!

Route::get('/home/{id}', [App\Http\Controllers\HomeController::class, 'book'])->name('home.book'); // Wildcards need to be after any other route!
Route::post('/home/{id}', [App\Http\Controllers\HomeController::class, 'rate']);