<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Str;
use App\Notifications\Invitation;
use Illuminate\Support\Carbon;

trait MemberTrait 
{
    private function sendInvitation(User $user, bool $isNewUser)
    {
        $token = Str::random(40);

        DB::table('team_user')->insert([
                'user_id' => $user->id,
                'team_id' => $this->teamId,
                'role' => request()->role
        ]);

        $this->storeToken($token, $user->id);
        
        $user->notify(new Invitation(
            Team::find($this->teamId),
            $token,
            $isNewUser
        ));

        return back()->with(sendToast('Member Invited'));

    }

    private function storeToken($token, $userId): void 
    {
        DB::table('invitation')->insert([
            'user_id'=>$userId,
            'token' => $token,
            'expiry'=> Carbon::now()->addDay()
        ]);
    }
}
