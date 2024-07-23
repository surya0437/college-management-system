<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $primaryKey = 'teacher_id';
    protected $fillable = [
        'roll_no',
        'fname',
        'lname',
        'gender',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'education',
        'specialization',
        'in_time',
        'working_hour',
        'out_time',
        'image',
        'status',
        'face',
    ];
}
