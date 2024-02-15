<?php

namespace App\Traits;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Exception;
use App\Models\Project;

trait ProjectExceptionTrait 
{
    private function addAiResToException($exception, $response) 
    {
        $exception->ai_solution = $response['choices'][0]['text'];
        $exception->save();
    }

    private function createPrompt( $exception) 
    {
        $prompt = 'I encountered an exception in my Laravel application. Here are the details:\n - Exception Class: {$exception->name}\n - Exception Message: {$exception->message}\n';
        if($exception->detail->app->php_version && $exception->detail->app->laravel_version) {
            $prompt .= " - PHP Version: {$exception->detail->app->php_version}\n - Laravel Version: {$exception->detail->app->laravel_version}\n Given these details,";
        } else {
            $prompt .= " - Programing language is PHP and framework is Laravel\n Given these details,";
        }
        $prompt .= "  what could be the possible cause of the exception and how can I resolve it?";

        return $prompt;
    }

    private function aiSolution($prompt, $apiKey) 
    {
        try{
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

    private function hasOpenaiKey(Project $project): bool
    {
        if(!$project->relationLoaded('config')){
            $project->load('config');
        }

        return $project->config->where('key', 'openai_key')->first()->values['key'] ? true : false;
    }
}
