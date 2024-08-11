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
        'category',
        'author',
        'periodic_id',
        'status'
    ];

    public function periodic()
    {
        return $this->belongsTo(Periodic::class, 'periodic_id', 'periodic_id');
    }
}
