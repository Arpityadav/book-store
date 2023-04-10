<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Book extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];
    public function searchableFields()
    {
        return ['title', 'genre', 'author', 'published'];
    }
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'genre' => $this->genre,
            'author' => $this->author,
            'published' => $this->published
        ];
    }

//    public function getRouteKeyName(): string
//    {
//        return 'title';
//    }
}
