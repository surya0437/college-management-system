<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassShift extends Model
{
    use HasFactory;
    protected $primaryKey = 'classShift_id';
    
    protected $fillable = [
        'name',
        'in_time',
        'out_time',
        'status',
    ];
}
