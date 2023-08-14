<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Team
 * @package App\Models\Team
 * 
 * @author Muhammad Imran Israr (mimranisrar6@gmail.com)
 */
class Team extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Define a many-to-many relationship between the current model and the User model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() 
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    /**
     * Attach multiple users to the team.
     *
     * This function attaches an array of user IDs to the team using the "users" relationship.
     *
     * @param array $userids An array of user IDs to be attached to the team.
     * @return void
     */
    public function createTeamUser(array $userids = [])
    {
        $this->users()->attach($userids);
    }

    /**
     * Check if the currently authenticated user can delete the team.
     *
     * @return bool Returns true if the user can delete the team, false otherwise.
     */
    public function canDelete()
    {
        $team_user = DB::table('team_user')
        ->where(['team_id' => $this->id, 'user_id' => auth()->id()])
        ->first();

        return $team_user && $team_user->role === 'owner';
    }
}
