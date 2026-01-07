<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymClass extends Model
{
    /** @use HasFactory<\Database\Factories\GymClassFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration',
        'max_capacity',
    ];
}
