<?php

namespace App\Http\Controllers;

use App\Models\Exception;
use App\Models\Project;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use \Illuminate\Contracts\View\View;

class ProjectExceptionController extends Controller
{
    /**
     * Display the index page for exceptions.
     *
     * @param Project $project The project object.
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Project $project): View 
    {
        return view('exceptions.index', [
            'project' => $project
        ]);
    }

    public function fetch() 
    {
        $query = $this->getFilterQuery(request());
        
        // todo:: add pagination
        $exceptions = $query->orderBy('updated_at', 'DESC')->get();
        
        $exceptions = $this->addChartData($exceptions);

        $project = Project::find(request()->project);

        return view('exceptions.partials.exception-card', ['project' => $project, 'exceptions' => $exceptions]);
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
        if(!config('app.openai_api_key') || config('app.openai_api_key') == "") {
            return response()->json(['success' => false, 'message' => 'OpenAI API key not found']);
        }

        try{
            $exception->load('detail');
            $prompt = $this->createPrompt($exception);
            $response = $this->aiSolution($prompt);
            $this->addAiResToException($exception, $response);
            return response()->json(['success' => true, 'data' => $response['choices'][0]['text']]);
        } catch(\Exception $e){
            // todo:: log error
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }

    private function addAiResToException($exception, $response) 
    {
        $exception->ai_solution = $response['choices'][0]['text'];
        $exception->save();
    }

    private function createPrompt( $exception) 
    {
        return "I encountered an exception in my Laravel application. Here are the details:\n - Exception Class: {$exception->name}\n - Exception Message: {$exception->message}\n - PHP Version: {$exception->detail->app->php_version}\n - Laravel Version: {$exception->detail->app->laravel_version}\n Given these details, what could be the possible cause of the exception and how can I resolve it?";
    }

    private function aiSolution($prompt) 
    {
        try{
            $apiKey = config('app.openai_api_key');
            $url = 'https://api.openai.com/v1/completions';
        
            $data = [
                'model' => 'gpt-3.5-turbo-instruct',
                'prompt' => $prompt,
                'temperature' => 0.5,
                'max_tokens' => 150,
                'top_p' => 1.0,
                'frequency_penalty' => 0.0,
                'presence_penalty' => 0.0,
            ];
        
            $client = new Client();
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ],
                'json' => $data,
            ]);
        
            $body = $response->getBody();
            $result = json_decode($body, true);
        
            return $result;
        } catch(\Exception $e){
            throw $e;
        }
    }

    private function addingTraceAndCodeSnippets($log): object
    {
        $rawTraces = json_decode($log->detail->trace);
        $rawCodeSnippets = json_decode($log->detail->code_snippet);

        $codeSnippets = [];
        foreach ($rawCodeSnippets as $codeSnippet) {
            array_push($codeSnippets, json_decode($codeSnippet));
        }

        $traces = [];
        foreach ($rawTraces as $trace) {
            array_push($traces, json_decode($trace));
        }

        $log->traces = $traces;
        $log->codeSnippets = $codeSnippets;

        return $log;
    }

    private function generateMainChrtData($query)
    {
        $dates = [];
        $total = [];

        foreach ($query as $q) {
            array_push($dates, $q->date);
            array_push($total, $q->total);
        }

        return [$dates, $total];
    }

    private function getFilterQuery($request): object
    {
        $query = Exception::query();
        
        $query->where('project_id', $request->project);

        $filter = null;

        if (!in_array($request->filter, ['snoozed', 'resolved'])) {
            if ($request->filter === '12h') {
                $filter = \Carbon\Carbon::now()->subHour(12);
            }

            if ($request->filter === 'day') {
                $filter = \Carbon\Carbon::now()->subDay();
            }

            if ($request->filter === 'week') {
                $filter = \Carbon\Carbon::now()->subWeek();
            }

            $query->where(['is_snoozed' => false, 'is_resolved' => false]);

            if ($filter) {
                $query->where('updated_at', '>=', $filter);
            }
        }

        if (in_array($request->filter, ['snoozed', 'resolved'])) {
            if ($request->filter === 'snoozed') {
                $query->where('is_snoozed', true);
            }

            if ($request->filter === 'resolved') {
                $query->where('is_resolved', true);
            }
        }

        return $query;
    }

    private function addChartData($logs)
    {
        /** IF FUNCTION INVOKED FROM FETCH METHOD */
        if ($logs instanceof Collection) {
            foreach ($logs as $log) {
                $log = $this->generateChartData($log);
            }

            return $logs;
        }

        return $this->generateChartData($logs);
    }

    private function generateChartData($log)
    {
        $times = [];
        foreach ($log->occurrence_times as $time) {
            $format = $time->format('m-d-Y');
            if (array_key_exists($format, $times)) {
                $times[$format] = $times[$format] + 1;
                continue;
            }
            $times[$format] = 1;
        }

        $log->chart = $times;
        $log->chartLabels = array_keys($times);
        $log->chartData = array_values($times);

        return $log;
    }
}
