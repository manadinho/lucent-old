<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExceptionDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getAppAttribute($value) 
    {
        return json_decode($value);
    }

    public function getUserAttribute($value) 
    {
        return json_decode($value);
    }

    public function getRequestAttribute($value)
    {
        return json_decode($value);
    }

}
