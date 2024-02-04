<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Models\ProjectConfig;
use App\Traits\ProjectTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class ProjectController
 * @package App\Http\Controllers\ProjectController
 * 
 * @author Muhammad Imran Israr (mimranisrar6@gmail.com)
 */
class ProjectController extends Controller
{
    use ProjectTrait;
    
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

        if(! canDo($request->team_id, $this->user->id, 'can_create_project')){
            return back()->with(sendToast('You do not have permission.', ERROR));
        }

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
     * Delete a project.
     *
     * @param Project $project The project to be deleted.
     * @return \Illuminate\Http\RedirectResponse The response to redirect back with a success message.
     */
    public function delete(Project $project): RedirectResponse
    {
        if(! canDo($project->team_id, auth()->id(), 'can_delete_project')){
            return back()->with(sendToast('You do not have permission.', ERROR));
        }

        $project->delete();

        return back()->with(sendToast('Project deleted.'));
    }

    /**
     * Display the configurations for a project.
     *
     * @param Project $project The project for which configurations are being displayed.
     * @return \Illuminate\Contracts\View\View The view containing the project configurations.
     */
    public function configurations(Project $project): View
    {
        $configurations = ProjectConfig::where('project_id', $project->id)->get();
        
        return view('team.project-config', [
            'project' => $project,
            'configurations' => $configurations
        ]);
    }

    /**
     * Generate a new key for the specified project and update the corresponding configuration.
     *
     * @param Project $project The project for which a new key is generated.
     * @return \Illuminate\Http\RedirectResponse A redirect response to the previous page with a toast message.
     */
    public function keyGenerate(Project $project): RedirectResponse
    {
        if(canDo($project->team_id, auth()->id(), 'can_generate_key')) {
            ProjectConfig::where(['project_id' => $project->id, 'key' => 'lucent_key'])->update(['values' => json_encode(['key' => $project->generatePrivateKey()])]);

            return back()->with(sendToast('Project key regenerated.'));
        }

        return back()->with(sendToast('You do not have permission.', ERROR));
    }
}
