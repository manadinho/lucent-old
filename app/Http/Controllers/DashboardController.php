<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() 
    {
        $team_ids = auth()->user()->teams()->pluck('id')->toArray();

        $projects = Project::whereIn('team_id', $team_ids)->withCount(['exceptions', 'resolvedExceptions'])->get();

        return view('dashboard', [
            'projects' => $projects
        ]);
    }
}
