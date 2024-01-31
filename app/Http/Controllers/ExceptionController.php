<?php

namespace App\Http\Controllers;

use App\Http\Services\ExceptionService;
use Exception;

/**
 * Class ExceptionController
 * @package App\Http\Controllers\ExceptionController
 * 
 * @author Muhammad Imran Israr (mimranisrar6@gmail.com)
 */
class ExceptionController extends Controller
{
    private $stack_trace = null;
    private $currentTime = null;
    private $service = null;
    private $request = null;
    private $user = null;
    private $app = null;

    public function __construct(ExceptionService $exceptionService)
    {
        $this->currentTime = \Carbon\Carbon::now()->toString();

        $this->service = $exceptionService;
    }

    /**
     * Register an exception by decoding the stack trace from the request and then updating or creating the exception in the database.
     *
     * @return string Returns 'success' on successful registration of the exception.
     */
    public function registerException()
    {
        try {
            $this->setter();

            [$isExist, $exception] = $this->updateOrCreateException();

            $this->service->set($exception);

            $this->service->notify();

            if (!$isExist) 
            {
                $this->service->createDetail($this->stack_trace->trace, $this->stack_trace->code_snippet, $this->request, $this->user, $this->app);
            }
            return 'success';
        } catch(Exception $e) {
            fast($e);
        }   
    }

    /**
     * Set values for stack trace, request, user, and app properties.
     *
     * This function parses the stack trace and sets the properties of the current object
     * with the decoded stack trace, request data, user data, and app data.
     *
     * @return void
     */
    private function setter(): void
    {
        $this->stack_trace = json_decode(request()->stack_trace);

        $this->request = request()->request_detail ?? "{}";

        $this->user = request()->user ?? "{}";
        
        $this->app = request()->app ?? "{}";
    }

    /**
     * Updates an existing exception record or creates a new one if not found.
     *
     * @return array An array containing a boolean indicating if an existing exception was found (true) or a new one was created (false),
     *               and the exception data (found or created).
     */
    private function updateOrCreateException(): array
    {
        $exception = $this->service->find($this->whereCondition());

        if ($exception) {
            $this->service->set($exception)->increaseOccurrence()->reopen()->save();

            return [true, $exception];
        }

        $exception = $this->service->create($this->dataNeedToCreate());

        return [false, $exception];
    }

    /**
     * Generate an array representing the where condition for a database query based on the stack trace properties.
     *
     * @return array An associative array representing the where condition.
     */
    private function whereCondition(): array
    {
        return [
            'name' => $this->stack_trace->exception_name,
            'message' => $this->stack_trace->message,
            'code' => $this->stack_trace->code,
            'file' => $this->stack_trace->file,
            'line' => $this->stack_trace->line,
            'project_id' => request()->project_id,
        ];
    }

    /**
     * Prepare an array of data needed to create a new exception occurrence.
     *
     * @return array An associative array containing the data to create a new exception occurrence.
     */
    private function dataNeedToCreate(): array
    {
        return [
            'name' => $this->stack_trace->exception_name,
            'message' => $this->stack_trace->message,
            'code' => $this->stack_trace->code,
            'file' => $this->stack_trace->file,
            'line' => $this->stack_trace->line,
            'project_id' => request()->project_id,
            'occurrence_times' => $this->currentTime,
            'severity' => 'Error',
        ];
    }
    
}
