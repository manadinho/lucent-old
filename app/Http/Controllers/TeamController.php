<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View 
    {  
        $user = User::where('id', auth()->id())->with('teams', function($q){
            return $q->withCount('users');
        })->first();

        return view('team.index', ['teams' => $user->teams]);
    }

    public function create()
    {
        request()->validate([
            'name' => 'required'
        ]);

        // create new team
        $team = Team::create([
            'name' => request()->name,
        ]);

        // add loggedin user as owner of the team
        $team->createTeamUser([auth()->id() => ['role' => 'owner']]);

        return redirect()->route('teams.index')->with(['toast' => true, 'status' => 'success', 'message' => 'Team created successfully.']);
    }

    public function delete($id) 
    {
        //Todo:: Need to check current user's role in this team
        Team::where('id', $id)->delete();

        return redirect()->route('teams.index')->with(['toast' => true, 'status' => 'success', 'message' => 'Team deleted successfully.']);
    }


    public function teamsInfo($id): View
    {
        $team = Team::where('id',$id)->with('users')->first();

        // foreach($teams->users as $user){
        //     if($user->pivot->user_id == auth()->id())
        //     $role = $user->pivot->role;
        // }

        return view('team.info',[
            'members' => $team->users,
            'teamName' => $team->name,
            // 'role' => $role
        ]);
    }

}
