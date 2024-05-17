<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "author",
        "isbn",
        "published_year"
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(user::class);
    }

    public static function getBooksAndUserCounts()
    {
        return self::withCount('users')->get()->map(function($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'isbn' => $book->isbn,
                'published_year' => $book->published_year,
                'users' => $book->users_count
            ];
        });
    }

}   
