<?php

namespace App\Http\Controllers;

use App\Models\Exception;
use App\Models\Project;
use App\Traits\ProjectExceptionTrait;
use Illuminate\Support\Facades\DB;
use \Illuminate\Contracts\View\View;

class ProjectExceptionController extends Controller
{
    use ProjectExceptionTrait;
    
    /**
     * Display the index page for exceptions.
     *
     * @param Project $project The project object.
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Project $project): View 
    {
        return view('exceptions.index', [
            'project' => $project,
        ]);
    }

    public function fetch() 
    {
        $query = $this->getFilterQuery(request());
        
        // todo:: add pagination
        $exceptions = $query->orderBy('updated_at', 'DESC')->get();
        
        $exceptions = $this->addChartData($exceptions);

        $project = Project::find(request()->project);

        return view('exceptions.partials.exception-card', ['project' => $project, 'exceptions' => $exceptions, 'hasOpenaiKey' => $this->hasOpenaiKey($project)]);
    }

    public function count()
    {
        $query = $this->getFilterQuery(request());

        return response()->json(['success' => true, 'data' => $query->count()]);
    }

    public function getMainChartData()
    {
        $query = $this->getFilterQuery(request());
        $query = $query->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        [$dates, $total] = $this->generateMainChrtData($query);
        return response()->json(['success' => true, 'data' => ['dates' => $dates, 'total' => $total]]);
    }

    public function getOne(Project $project, $id)
    {
        $exception = Exception::with('detail')->find($id);
        
        $exception = $this->addChartData($exception);

        $exception = $this->addingTraceAndCodeSnippets($exception);
        
        return view('exceptions.detail', [
            'log' => $exception,
            'project' => $project,
        ]);
    }

    public function delete()
    {
        $exception = Exception::where('id', request()->id)->delete();

        if ($exception) {
            return response()->json(['success' => true, 'message' => 'Exception deleted successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Something went wrong']);
    }

    public function markSnoozed()
    {
        $exception = Exception::where('id', request()->id)->first();
        $exception->is_snoozed = !$exception->is_snoozed;
        $exception->save();

        if ($exception) {
            return response()->json(['success' => true, 'message' => 'Exception snoozed successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Something went wrong']);
    }

    public function markResolved()
    {
        $exception = Exception::where('id', request()->id)->first();

        $exception->is_resolved = !$exception->is_resolved;
        
        $exception->save();

        if ($exception) {
            return response()->json(['success' => true, 'message' => 'Exception resolved successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Something went wrong']);
    }

    public function generateSolution(Exception $exception) 
    {
        try{
            $project = Project::with('config')->find($exception->project_id);
            $openaiKey = $project->config->where('key', 'openai_key')->first()->values['key'];
            if(!$openaiKey) {
                return response()->json(['success' => false, 'message' => 'OpenAI API key not found']);
            }

            $exception->load('detail');
            $prompt = $this->createPrompt($exception);
            $response = $this->aiSolution($prompt, $openaiKey);
            $this->addAiResToException($exception, $response);
            return response()->json(['success' => true, 'data' => $response['choices'][0]['text']]);
        } catch(\Exception $e){
            // todo:: log error
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }

}
