<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Class TeamController
 * @package App\Http\Controllers\TeamController
 * 
 * @author Muhammad Imran Israr (mimranisrar6@gmail.com)
 */
class TeamController extends Controller
{
    /**
     * Display the user's teams on the index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View 
    {  
        $user = User::where('id', auth()->id())->with('teams', function($q){
            return $q->withCount('users');
        })->first();

        return view('team.index', ['teams' => $user->teams]);
    }

    /**
     * Create a new team and associate the logged-in user as the owner.
     *
     * This function validates the input data, creates a new team, assigns the logged-in user as the owner,
     * and then redirects to the teams index page with a success message.
     *
     * @return \Illuminate\Http\RedirectResponse Redirects to the teams index page with a success message.
     */
    public function create()
    {
        request()->validate([
            'name' => 'required'
        ]);

        // create new team
        $team = Team::create([
            'name' => $this->getUniqueName(request()->name),
        ]);

        // add loggedin user as owner of the team
        $team->createTeamUser([
            auth()->id() => ['role' => 'owner']
        ]);

        return redirect()->route('teams.index')->with(['toast' => true, 'status' => 'success', 'message' => 'Team created successfully.']);
    }

    /**
     * Generate a unique name by appending a number to the input name if it already exists in the database.
     *
     * @param string $name The original name to check for uniqueness.
     * @return string The unique name.
     */
    private function getUniqueName(string $name): string
    {
        $originalName = $name;

        $counter = 1;

        while (Team::where('name', $name)->exists()) {
            $name = $originalName . '-' . $counter;

            $counter++;
        }

        return $name;
    }

    /**
     * Delete a team.
     *
     * This function deletes the provided team after checking the current user's role in the team.
     *
     * @param Team $team The team to be deleted.
     * @return \Illuminate\Http\RedirectResponse Redirects to the teams index page with a success message.
     */
    public function delete(Team $team) 
    {
        if ($team->canDelete()) {
            $team->delete();

            return redirect()->route('teams.index')->with(['toast' => true, 'status' => 'success', 'message' => 'Team deleted successfully.']);
        }

        return redirect()->route('teams.index')->with(['toast' => true, 'status' => 'error', 'message' => 'Sorry, You cannot delete this team.']);
    }

    /**
     * Retrieve and display information about a team.
     *
     * This function fetches information about a team based on its ID and
     * displays the team members and team name.
     *
     * @param int $id The ID of the team to retrieve information for.
     * @return \Illuminate\View\View The view containing team information.
     */
    public function teamsInfo($id): View
    {
        $team = Team::where('id',$id)->with('users')->first();

        return view('team.info',[
            'members' => $team->users,
            'teamName' => $team->name,
            'isTeamOwner' => $this->isTeamOwner($team)
        ]);
    }

    /**
     * Check if the authenticated user is the owner of a given team.
     *
     * This function queries the 'team_user' table in the database to determine
     * if the user with the currently authenticated ID is the owner of the provided team.
     *
     * @param Team $team The team object for which ownership needs to be checked.
     * @return bool Returns true if the authenticated user is the owner of the team, false otherwise.
     */
    private function isTeamOwner(Team $team): bool
    {
        return DB::table('team_user')->where(['user_id' => auth()->id(), 'team_id' => $team->id])->first()->role === 'owner';
    }

}
