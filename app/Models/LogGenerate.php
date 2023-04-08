<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogGenerate extends Model
{
    use HasFactory;
    protected $fillable = [
        'activity',
        'status'
    ];
}
