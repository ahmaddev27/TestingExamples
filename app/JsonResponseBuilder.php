<?php

namespace App;

trait JsonResponseBuilder
{

    public function successResponse(string $message, $data = null, int $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'code' => $code,
        ], $code);
    }

    public function errorResponse(string $message, $data = null, int $code = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
            'code' => $code,
        ], $code);
    }

    public function validationErrorResponse(array $errors, string $message = 'Validation failed', int $code = 422)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => ['errors' => $errors],
            'code' => $code,
        ], $code);
    }
}
