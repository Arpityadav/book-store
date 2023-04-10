<?php

use App\Http\Controllers\BooksController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [BooksController::class, 'home'])->name('books.home');
Route::get('/books', [BooksController::class, 'index'])->name('books.index');
Route::get('/books/create', [BooksController::class, 'create'])->name('books.create');
Route::get('/books/{book}', [BooksController::class, 'show'])->name('books.show');
Route::post('/books', [BooksController::class, 'store'])->name('books.store');
Route::delete('/books/{book}', [BooksController::class, 'destroy'])->name('books.destroy');
Route::get('/books/{book}/edit', [BooksController::class, 'edit'])->name('books.edit');
Route::patch('/books/{book}', [BooksController::class, 'update'])->name('books.update');

// Disable the registration
Auth::routes(['register' => false, 'reset' => false]);
