<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
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

    public function attachBookToUser(Request $request, $userId, $bookId)
    {
        // print_r($request->input('name'));
        // print_r($request->all());
        $user = User::findOrFail($userId);
        $book = Book::findOrFail($bookId);

        $user->books()->attach($book);

        return response()->json(['message' => 'Book attached to user successfully'], 200);
    }

    public function detachBookFromUser(Request $request, $userId, $bookId)
    {
        $user = User::findOrFail($userId);
        $book = Book::findOrFail($bookId);

        $user->books()->detach($book);

        return response()->json(['message' => 'Book detached from user successfully'], 200);
    }

    public function getUserBooks($userId)
    {
        $user = User::with('books')->findOrFail($userId);
        return response()->json($user->books, 200);
    }

    public function getUsersWithBookId($bookId)
    {
        $book = Book::with('users')->findOrFail($bookId);
        return response()->json($book->users);
    }

    public function getBooksWithUserCounts()
    {
        $books = Book::getBooksAndUserCounts();
        return response()->json($books);
    }
    
    public function getUsersWithSameBook($bookId)
    {
        $book = Book::findOrFail($bookId);
        $usersCount = User::whereHas('books', function ($query) use ($bookId) {
            $query->where('book_id', $bookId);
        })->count();

        return response()->json([
            'book' => $book,
            'users_count' => $usersCount,
        ]);
    }
}
