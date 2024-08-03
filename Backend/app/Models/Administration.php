<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administration extends Model
{
    use HasFactory;
    protected $primaryKey = 'administration_id';
    protected $fillable = [
        'fname',
        'lname',
        'gender',
        'email',
        'phone',
        'address',
        'password',
        'role_id',
        'status',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
}
