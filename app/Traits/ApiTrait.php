<?php

namespace App\Traits;

trait ApiTrait
{
    public static function successMessage(string $message = "", int $statusCode = 200, $data = [])
    {
        return response()->json([
            'result' => true,
            'message' => $message,
            'data' => (object) [],  
            'errors' => (object) [],
        ], $statusCode);
    }

    public static function errorMessage(array $errors, string $message = "", int $statusCode = 422)
    {
        return response()->json([
            'result' => false,
            'message' => $message,
            'data' => (object) [],  
            'errors' => (object) $errors,
        ], $statusCode);
    }

    public static function data(array $data, string $message = "", int $statusCode = 200)
    {
        return response()->json([
            'result' => true,
            'message' => $message,
            'data' => $data,  // Pass the data directly, no need to cast as (object)
            'errors' => (object) [],
        ], $statusCode);
    }
}
