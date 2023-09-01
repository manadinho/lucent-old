<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exception extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getOccurrenceTimesAttribute($value)
    {

        $rawTimes =  explode(',', $value);

        $times = [];
        foreach ($rawTimes as $time) {
            array_push($times, \Carbon\Carbon::parse($time));
        }

        return $times;
    }

    public function detail()
    {
        return $this->hasOne(ExceptionDetail::class);
    }
}
