<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    /**
     * Generate a private key.
     *
     * @return string The generated private key.
     */
    public function generatePrivateKey(): string 
    {
        return Str::random(32);
    }

    public function exceptions() 
    {
        return $this->hasMany(Exception::class);
    }

    public function resolvedExceptions() 
    {
        return $this->hasMany(Exception::class)->where('is_resolved', 1);
    }

    public function config() 
    {
        return $this->hasMany(ProjectConfig::class);
    }

}
