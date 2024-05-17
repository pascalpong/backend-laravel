<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);
Route::post('/books', [BookController::class, 'store']);
Route::put('/books/{id}', [BookController::class, 'update']);
Route::delete('/books/{id}', [BookController::class, 'destroy']);

Route::post('/users/{userId}/books/{bookId}', [BookController::class, 'attachBookToUser']);
Route::delete('/users/{userId}/books/{bookId}', [BookController::class, 'detachBookFromUser']);
Route::get('/users/{userId}/books', [BookController::class, 'getUserBooks']);
Route::get('/users/{bookId}/book', [BookController::class, 'getUsersWithBookId']);
Route::get('/book/{bookId}/users', [BookController::class, 'getUsersWithSameBook']);
Route::get('/books-with-user-counts', [BookController::class, 'getBooksWithUserCounts']);