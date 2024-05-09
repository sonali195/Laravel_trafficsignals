<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignalLight extends Model
{
    use HasFactory;

    protected $fillable = ['sequence', 'green_interval', 'yellow_interval'];

    protected $casts = [
        'sequence' => 'json',
    ];
}
