<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $primaryKey = 'subject_id';

    protected $fillable = [
        'name',
        'program_id',
        'periodic_id',
        'status'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }

    public function periodic()
    {
        return $this->belongsTo(Periodic::class, 'periodic_id', 'periodic_id');
    }
}
