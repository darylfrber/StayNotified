<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'time', 'title', 'confirmed',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'confirmed' => 'boolean',
    ];
}
