<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Exception thrown when a requested resource is not found.
 * Returns a 404 HTTP status code with a descriptive message.
 */
class ResourceNotFoundException extends Exception
{
    /**
     * Create a new ResourceNotFoundException instance.
     *
     * @param string $resource The type of resource that was not found
     * @param mixed $identifier The identifier used in the search
     * @return void
     */
    public function __construct(string $resource = 'Resource', mixed $identifier = null)
    {
        $message = $identifier 
            ? "{$resource} with identifier '{$identifier}' not found."
            : "{$resource} not found.";
            
        parent::__construct($message, 404);
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'Not Found',
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
