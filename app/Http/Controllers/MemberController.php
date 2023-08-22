<?php

namespace App\Http\Controllers;
use App\Http\Requests\MemberRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Str;
use App\Notifications\Invitation;
use Illuminate\Support\Carbon;


class MemberController extends Controller
{   
    protected $teamId;
    
    public function add(MemberRequest $req)
    {
        $this->teamId = $req->teamId;

        if(!isTeamOwner($this->teamId)) {
            return back()->with(sendToast('You cannot add member in current Team', ERROR));
        }

        $user = User::where('email', $req->email)->with('teams')->first();

        $member = $user ? $user->teams->where('id',$req->teamId)->first() : null;
        
        if (!$member) {
            $isNewUser = false;
            if(!$user) {
                $isNewUser = true;
                $user = User::create([
                    'email' => $req->email,
                ]);
            }

            return  $this->sendInvitation($user, $isNewUser);    
        }
      
        return back()->with(sendToast('Already existed in current Team',ERROR));         
    }

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

    public function remove($user_id, $team_id)
    {  
        $authId = auth()->id();

        $member =  DB::table('team_user')
                    ->where('user_id',$authId)
                    ->where('team_id',$team_id)
                    ->first();

        if (($member->role != 'owner' && $user_id != $authId) || ($member->role == 'owner' && $user_id == $authId)) {
            return back()->with(sendToast('Action not allowed',ERROR));
        }                

        DB::table('team_user')
        ->where('team_id',$team_id)
        ->where('user_id',$user_id)
        ->delete();

        return redirect()->route('teams.index')->with(sendToast('Member removed successfully'));
            
    }

}
