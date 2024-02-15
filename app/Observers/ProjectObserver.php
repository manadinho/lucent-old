<?php

namespace App\Observers;

use App\Models\Project;
use App\Models\ProjectConfig;

/**
 * Class ProjectObserver
 * @package App\Observers\ProjectObserver
 * 
 * @author Muhammad Imran Israr (mimranisrar6@gmail.com)
 */
class ProjectObserver
{
    private $private_key = '';

    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        $this->private_key = $project->generatePrivateKey();

        ProjectConfig::insert($this->generateConfigurations($project));
    }

    /**
     * Generate configurations for a project.
     *
     * @param Project $project The project for which configurations are generated.
     * @return array An array of configurations.
     */
    private function generateConfigurations(Project $project): array 
    {
        return [
            [
                'project_id' => $project->id,
                'key' => 'lucent_key',
                'values' => json_encode(['key' => $this->private_key])
            ],
            [
                'project_id' => $project->id,
                'key' => 'openai_key',
                'values' => json_encode(['key' => ''])
            ],
            [
                'project_id' => $project->id,
                'key' => 'gemini_key',
                'values' => json_encode(['key' => ''])
            ],
            [
                'project_id' => $project->id,
                'key' => 'notifications',
                'values' => json_encode(['email' => '', 'slack' => '', 'webhooks' => '', 'sms' => ''])
            ],
            [
                'project_id' => $project->id,
                'key' => 'visitor_access', 
                'values' => json_encode(['emails' => ''])
            ],
            [
                'project_id' => $project->id,
                'key' => 'github',
                'values' => json_encode([])
            ]
        ];
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        ProjectConfig::where('project_id', $project->id)->delete();
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
