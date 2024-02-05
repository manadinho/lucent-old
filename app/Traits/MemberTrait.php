<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\Models\User;

trait MemberTrait 
{
    private function addUserToTeam(User $user)
    {
        DB::table('team_user')->insert([
                'user_id' => $user->id,
                'team_id' => $this->teamId,
                'role' => request()->role
        ]);

        return back()->with(sendToast('Member Invited'));
    }

    private function getfirstUser($where = []) 
    {
        return User::where($where)->with('teams')->first();
    }
}
