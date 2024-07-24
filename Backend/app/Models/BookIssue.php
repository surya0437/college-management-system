<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookIssue extends Model
{
    use HasFactory;
    protected $primaryKey = 'issues_id';

    protected $fillable = [
        'student_id',
        'book_id',
        'issues_date',
        'return_date',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id', 'student_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }
}