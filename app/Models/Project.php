<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Project
 * @package App\Models\Project
 * 
 * @author Muhammad Imran Israr (mimranisrar6@gmail.com)
 */
class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
