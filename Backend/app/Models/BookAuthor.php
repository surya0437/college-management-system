<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookAuthor extends Model
{
    use HasFactory;
    protected $primaryKey = 'author_id';

    protected $fillable = [
        'name',
        'status',
    ];
}
