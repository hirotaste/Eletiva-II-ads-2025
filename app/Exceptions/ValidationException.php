<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Exception thrown when business logic validation fails.
 * Returns a 422 HTTP status code with validation error details.
 */
class ValidationException extends Exception
{
    /**
     * @var array<string, array<string>> Validation errors
     */
    protected array $errors;

    /**
     * Create a new ValidationException instance.
     *
     * @param string $message The main error message
     * @param array<string, array<string>> $errors Detailed validation errors
     * @return void
     */
    public function __construct(string $message = 'Validation failed', array $errors = [])
    {
        parent::__construct($message, 422);
        $this->errors = $errors;
    }

    /**
     * Get the validation errors.
     *
     * @return array<string, array<string>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'Validation Error',
            'message' => $this->getMessage(),
            'errors' => $this->errors,
        ], $this->getCode());
    }
}
