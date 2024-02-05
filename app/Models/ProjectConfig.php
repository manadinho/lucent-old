<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectConfig extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'values' => 'array'
    ];
}
