<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function users() 
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }
    public function createTeamUser(array $userids = [])
    {
        $this->users()->attach($userids);
    }
}
