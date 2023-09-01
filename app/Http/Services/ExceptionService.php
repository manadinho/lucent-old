<?php

namespace App\Http\Services;

use App\Models\Exception;
use App\Models\ExceptionDetail;

/**
 * Class ExceptionService
 * @package App\Http\Services\ExceptionService
 * 
 * @author Muhammad Imran Israr (mimranisrar6@gmail.com)
 */
class ExceptionService 
{
    private $exception = null;

    /**
     * Find the first occurrence of an Exception record based on the specified conditions.
     *
     * @param array $where An associative array representing the conditions for the search.
     *                     Each key represents the column name, and its corresponding value represents
     *                     the condition to be matched.
     *                     Example: ['id' => 1, 'status' => 'active']
     * @return Exception|null Returns the first matching Exception record or null if none is found.
     */
    public function find(array $where = []) 
    {
        return Exception::where($where)->first();
    }

    /**
     * Set the exception to be handled by the ExceptionService.
     *
     * @param Exception $exception The Exception to be handled.
     * @return ExceptionService Returns the current instance of ExceptionService for method chaining.
     */
    public function set(Exception $exception): ExceptionService
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * Increases the occurrence count of the exception by a specified value.
     *
     * @param int $count The value to increase the occurrence count by. Default is 1.
     * @return ExceptionService Returns the current instance of ExceptionService for method chaining.
     */
    public function increaseOccurrence(int $count = 1): ExceptionService
    {
        $currentTime = \Carbon\Carbon::now()->toString();

        $this->exception->occurrence_count = $this->exception->occurrence_count + $count;

        $this->exception->occurrence_times = implode(',', $this->exception->occurrence_times).','.$currentTime;

        return $this;
    }

    /**
     * Reopen the exception and update the reopen count.
     *
     * @param int $count The number of times to reopen the exception (default is 1).
     * @return ExceptionService Returns the current instance of the ExceptionService class.
     */
    public function reopen(int $count = 1): ExceptionService
    {
        if ($this->exception->is_resolved) {
            $this->exception->is_resolved = false;
            $this->exception->reopen_count = $this->exception->reopen_count + 1;
        }

        return $this;
    }

    /**
     * Save the data using the exception object.
     *
     * This method is responsible for saving the data using the exception object.
     *
     * @return void
     */
    public function save(): void 
    {
        $this->exception->save();
    }

    /**
     * Create a new Exception instance with the provided data.
     *
     * @param array $data The data to create the Exception.
     *
     * @return Exception The newly created Exception instance.
     */
    public function create(array $data): Exception
    {
        return Exception::create($data);
    }

    /**
     * Notify about the exception.
     *
     * This function sends notifications about the exception via email, slack, or other configurations
     * that the user has selected against this project. If the exception is snoozed, no notifications
     * will be sent.
     *
     * @return void
     */
    public function notify(): void
    {
        if (!$this->exception->is_snoozed) 
        {
            // Todo:: implement notify about exception via email, slack or other configrtions that user
            // had selected against this project
        }
    }

    /**
     * Create a new ExceptionDetail instance and associate it with the current exception.
     *
     * @param string $trace The trace information related to the exception.
     * @param string $code_snippet The code snippet causing the exception.
     * @param string $request The request information at the time of the exception.
     * @param string $user The user information associated with the exception.
     * @param string $app The application information related to the exception.
     * @return ExceptionDetail The newly created ExceptionDetail instance.
     */
    public function createDetail(string $trace, string $code_snippet, string $request, string $user, string $app): ExceptionDetail
    {
        return ExceptionDetail::create([
            'exception_id' => $this->exception->id,
            'trace' => $trace,
            'code_snippet' => $code_snippet,
            'request' => $request,
            'user' => $user,
            'app' => $app,
        ]);
    }
}
