<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'destroy', 'edit', 'update']);
    }

    public function home()
    {
//        dd(request()->all());
        if (request()->wantsJson()) {

            $search = request('search');

            $query = Book::where(function ($query) use ($search) {
                $query->where('title', 'like', '%'. $search .'%');
                $query->orWhere('isbn', 'like', '%'. $search .'%');
                $query->orWhere('author', 'like', '%'. $search .'%');
            });
            if (request()->input('genres') != '') {

                $query = $query->whereIn('genre', explode(',', request('genres')));
            }
            if (request('from_date')) {
                $query = $query->whereDate('published', '>=', Carbon::parse(request('from_date')));
            }
            if (request('to_date')) {
                $query = $query->whereDate('published', '<=', Carbon::parse(request('to_date')));
            }

                $query = $query->latest()
                ->paginate(10);


//            dd($query);
            return response()->json($query);
        }
        $genres = $this->getGenres();

        return view('books.home', compact('genres'));
    }

    public function index()
    {
//        dd(request()->all());
        if (request()->wantsJson()) {
            $books = Book::query();

            if (request()->input('search')) {
                $books = Book::search(request('search'));
            }

//            if (request()->input('genres')) {
//                $books = $books->where('genre', request('genres'));
//            }

            $books = $books->orderBy('created_at', 'desc')->paginate(10);

            return response()->json($books);
        }
        return view('books.index');
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function create()
    {
        $genres = $this->getGenres();

        return view('books.create', compact('genres'));
    }

    public function store()
    {
        $validator = Validator::make(request('form'), [
            'title' => ['required', 'max:255'],
            'author' => ['required', 'max:255'],
            'isbn' => ['required', 'numeric'],
            'publisher' => ['required'],
            'description' => ['required'],
            'genre' => ['required'],
            'image' => ['required', 'image'],
            'published' => ['required', 'date_format:Y-m-d\TH:i']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all(),  422);
        }

        $book = Book::create($validator->safe()->except(['image', 'errors']));

        if(request('form.image')) {
            $file_name = time() . '_' . request('form.image')->getClientOriginalName();
            $file_path = request('form.image')->storeAs('uploads', $file_name, 'public');

            $book->image = '/storage/' . $file_path;
            $book->save();
        }

        return route('books.show', ['book' => $book->id]);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return route('books.index');
    }

    public function edit(Book $book)
    {
        $genres = $this->getGenres();

        return view('books.edit', compact('genres', 'book'));
    }

    public function update(Book $book)
    {
        $validator = Validator::make(request('form'), [
            'title' => ['required', 'max:255'],
            'author' => ['required', 'max:255'],
            'isbn' => ['required', 'numeric'],
            'publisher' => ['required'],
            'description' => ['required'],
            'genre' => ['required'],
            'published' => ['required', 'date']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all(),  422);
        }

        $book->update($validator->safe()->except(['image', 'errors']));

        if(request()->has('form.image') && gettype(request('form.image')) !== "string") {
            $pathWithoutStorage = str_replace('/storage', "", $book->image);

            if (Storage::disk('public')->exists($pathWithoutStorage)) {
                Storage::disk('public')->delete($pathWithoutStorage);
            }
            $file_name = time() . '_' . request('form.image')->getClientOriginalName();
            $file_path = request('form.image')->storeAs('uploads', $file_name, 'public');

            $book->image = '/storage/' . $file_path;
            $book->save();
        }

        return route('books.show', ['book' => $book->id]);
    }


    /**
     * @return mixed
     */
    public function getGenres()
    {
        return Book::select('genre')->distinct()->pluck('genre')->toArray();
    }
}
