<?php

use Illuminate\Support\Facades\DB;

define('ERROR','error');


function sendToast($msg, $status = 'success')
{
    return [
        'toast' => true, 
        'status' => $status, 
        'message' => $msg
    ];
}

/**
 * Check if the authenticated user is the owner of a given team.
 *
 * This function queries the 'team_user' table in the database to determine
 * if the user with the currently authenticated ID is the owner of the provided team.
 *
 * @param $teamId The teamId of the given team.
 * @return bool Returns true if the authenticated user is the owner of the team, false otherwise.
 */
function isTeamOwner($teamId): bool 
{
    return DB::table('team_user')->where(['user_id' => auth()->id(), 'team_id' => $teamId])->first()->role === 'owner';
}
