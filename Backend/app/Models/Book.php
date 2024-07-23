<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $primaryKey = 'book_id';

    protected $fillable = [
        'name',
        'quantity',
        'category_id',
        'author_id',
        'periodic_id',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'category_id', 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(BookAuthor::class, 'author_id', 'author_id');
    }

    public function periodic()
    {
        return $this->belongsTo(Periodic::class, 'periodic_id', 'periodic_id');
    }
}
