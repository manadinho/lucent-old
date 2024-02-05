<?php

namespace App\Http\Controllers;
use App\Http\Requests\MemberRequest;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Traits\MemberTrait;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{   
    use MemberTrait;

    protected $teamId;
    
    public function add(MemberRequest $req)
    {
        if(! canDo($req->teamId, auth()->id(), 'can_add_member')){
            return back()->with(sendToast('You do not have permission.', ERROR));
        }

        $this->teamId = $req->teamId;

        $user = $this->getfirstUser(['email' => $req->email]);

        $member = $user ? $user->teams->where('id',$req->teamId)->first() : null;
        
        if (!$member) {
            if(!$user) {
                $user = User::create([
                    'name' => $req->name,
                    'email' => $req->email,
                    'password' => Hash::make($req->password),
                ]);
            }

            return  $this->addUserToTeam($user);    
        }
      
        return back()->with(sendToast('Already existed in current Team',ERROR));         
    }

    public function remove($user_id, $team_id)
    {  
        $authId = auth()->id();

        if(! canDo($team_id, $authId, 'can_remove_member')){
            return back()->with(sendToast('You do not have permission.', ERROR));
        }

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
