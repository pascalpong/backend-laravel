<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Get all books
    public function index()
    {
        return Book::all();
    }

    // Get a specific book
    public function show($id)
    {
        return Book::find($id);
    }

    // Create a new book
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:13|unique:books',
            'published_year' => 'required|integer',
        ]);

        $book = Book::create($validatedData);

        return response()->json($book, 201);
    }

    // Update an existing book
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'isbn' => 'sometimes|required|string|max:13|unique:books,isbn,'.$book->id,
            'published_year' => 'sometimes|required|integer',
        ]);

        $book->update($validatedData);

        return response()->json($book);
    }

    // Delete a book
    public function destroy($id)
    {
        Book::destroy($id);

        return response()->json(null, 204);
    }
}
