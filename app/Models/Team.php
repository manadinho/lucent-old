<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    public function users(): BelongsToMany 
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    /**
     * Define a relationship between Team and Project models.
     *
     * This function establishes a "hasMany" relationship between the Team model
     * and the Project model. It allows a Team instance to have multiple associated
     * Project instances.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
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
     * Check if the specified user is the owner of the team.
     *
     * @param int $user_id The ID of the user to check.
     * @return bool Returns `true` if the user is the owner of the team, `false` otherwise.
     */
    public function isUserOwner(int $user_id): bool
    {
        $team_user = DB::table('team_user')
        ->where(['team_id' => $this->id, 'user_id' => $user_id])
        ->first();

        return $team_user && $team_user->role === 'owner';
    }
}
