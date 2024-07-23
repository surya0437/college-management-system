<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodic extends Model
{
    use HasFactory;

    protected $primaryKey = 'periodic_id';

    protected $fillable = [
        'name',
        'status'
    ];

}
