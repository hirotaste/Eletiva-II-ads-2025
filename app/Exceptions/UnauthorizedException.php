<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Exception thrown when a user is not authorized to perform an action.
 * Returns a 403 HTTP status code with a descriptive message.
 */
class UnauthorizedException extends Exception
{
    /**
     * Create a new UnauthorizedException instance.
     *
     * @param string $message The error message
     * @return void
     */
    public function __construct(string $message = 'You are not authorized to perform this action.')
    {
        parent::__construct($message, 403);
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'Forbidden',
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
