<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index() 
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
}
