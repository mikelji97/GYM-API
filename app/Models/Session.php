<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'gym_sessions';

    protected $fillable = [
        'gym_class_id',
        'date',
        'start_time',
        'end_time',
        'room',
        'max_capacity',
        'current_bookings',
    ];
}
