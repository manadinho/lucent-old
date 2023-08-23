<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;

/**
 * Class ProjectController
 * @package App\Http\Controllers\ProjectController
 * 
 * @author Muhammad Imran Israr (mimranisrar6@gmail.com)
 */
class ProjectController extends Controller
{
    public $user;

    /**
     * Create or update a project based on the given request.
     *
     * @param ProjectRequest $request The request containing project data.
     * @return \Illuminate\Http\RedirectResponse The response with a toast message.
     */
    public function create(ProjectRequest $request): RedirectResponse
    {
        $this->user = auth()->user();

        $message = $request->id ? 'Project updated.' : 'Project created.';

        Project::updateOrCreate(['id' => $request->id], [
            'name' => $this->getUniqueName($request->name),
            'team_id' => $request->team_id,
            'environment' => $request->environment,
            'user_id' => $this->user->id
        ]);

        return back()->with(sendToast($message));
    }

    /**
     * Generate a unique name by appending a number to the input name if it already exists in the database.
     *
     * @param string $name The original name to check for uniqueness.
     * @return string The unique name.
     */
    private function getUniqueName(string $name): string
    {
        // TO CHECK IF UPDATE REQUEST AND USER IS NOT UPDATIONG NAME
        if (request()->id) {
            if (Project::find(request()->id)->name ===  $name) {
                return $name;
            }
        }

        $originalName = $name;

        $counter = 1;

        while (Project::where(['name' => $name, 'user_id' => $this->user->id, 'team_id' => request()->team_id])->exists()) {
            $name = $originalName . '-' . $counter;

            $counter++;
        }

        return $name;
    }

    /**
     * Delete a project.
     *
     * @param Project $project The project to be deleted.
     * @return \Illuminate\Http\RedirectResponse The response to redirect back with a success message.
     */
    public function delete(Project $project): RedirectResponse
    {
        $project->delete();

        return back()->with(sendToast('Project deleted.'));
    }
}
