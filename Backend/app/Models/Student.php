<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
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
        'classShift_id',
        'image',
        'status',
        'face',
    ];

    protected $hidden = [
        'password',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }

    public function classShift()
    {
        return $this->belongsTo(ClassShift::class, 'classShift_id', 'classShift_id');
    }
}
