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

/**
 * Check if a user has a specific permission within a team.
 *
 * This function checks whether a user with a given role within a team has a specific permission.
 *
 * @param string $team_id The ID of the team.
 * @param string $user_id The ID of the user.
 * @param string $permission_name The name of the permission to check.
 *
 * @return bool Returns true if the user has the specified permission, false otherwise.
 */
function canDo(string $team_id, string $user_id, string $permission_name): bool
{
    try{
        $role = DB::table('team_user')->where(['team_id' => $team_id, 'user_id' => $user_id])->first()->role;

        return config('lucentpermissions.'.$permission_name)[$role];

    } catch(Exception $e){
        return false;
    }
}

function formatAiResponse($text) 
{
    $textWithBr = nl2br($text, false);

    return preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $textWithBr);
}
