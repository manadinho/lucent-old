<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;

/**
 * Class ProjectController
 * @package App\Http\Controllers\ProjectController
 * 
 * @author Muhammad Imran Israr (mimranisrar6@gmail.com)
 */
class ProjectController extends Controller
{
    public function create(ProjectRequest $request)
    {
        $message = $request->id ? 'Project updated.' : 'Project created.';

        Project::updateOrCreate(['id' => $request->id], [
            'name' => $request->name,
            'team_id' => $request->team_id,
            'environment' => $request->environment,
            'user_id' => auth()->id()
        ]);

        return back()->with(sendToast($message));
    }

    public function delete(Project $project)
    {
        $project->delete();

        return back()->with(sendToast('Project deleted.'));
    }
}
