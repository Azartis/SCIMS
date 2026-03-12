<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Base Service Class
 * 
 * Provides common functionality for all services:
 * - Logging
 * - Error handling
 * - Response formatting
 * 
 * @package App\Services
 */
abstract class BaseService
{
    /**
     * Log a message to the application log
     * 
     * @param string $message
     * @param string $level
     * @param array $context
     * @return void
     */
    protected function log(string $message, string $level = 'info', array $context = []): void
    {
        Log::{$level}(
            "[" . class_basename($this) . "] " . $message,
            $context
        );
    }

    /**
     * Handle an exception safely and log it
     * 
     * @param \Exception $e
     * @param string $userMessage
     * @return array
     */
    protected function handleException(\Exception $e, string $userMessage = 'An error occurred'): array
    {
        $this->log('Exception: ' . $e->getMessage(), 'error', [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return [
            'success' => false,
            'message' => $userMessage,
            'error' => config('app.debug') ? $e->getMessage() : null,
        ];
    }

    /**
     * Format a success response
     * 
     * @param mixed $data
     * @param string $message
     * @return array
     */
    protected function successResponse($data = null, string $message = 'Success'): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    /**
     * Format an error response
     * 
     * @param string $message
     * @param array $errors
     * @return array
     */
    protected function errorResponse(string $message = 'Error', array $errors = []): array
    {
        return [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ];
    }
}
