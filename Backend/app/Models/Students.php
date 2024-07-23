<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;
    protected $primaryKey = 'student_id';
    protected $fillable = [
        'roll_no',
        'fname',
        'lname',
        'gender',
        'email',
        'phone',
        'address',
        'password',
        'date_of_birth',
        'program_id',
        'image',
        'status',
        'face',
    ];
}
