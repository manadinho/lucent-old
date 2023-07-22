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
        return $this->belongsToMany(User::class, 'team_user', 'user_id', 'team_id')->withPivot('role');
    }

    public function createTeamUser(array $userids = [])
    {
        $this->users()->sync($userids);
    }
}
