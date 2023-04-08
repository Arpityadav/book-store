<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function index()
    {

        if (request()->wantsJson()) {
            $books = Book::paginate(10);

            return response()->json($books);
        }
//        dd($books);
        return view('books.index');
    }
}
