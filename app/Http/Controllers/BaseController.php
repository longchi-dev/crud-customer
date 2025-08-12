<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected function successResponse($data = null, string $message = null, int $statusCode = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    protected function errorResponse($error = null, string $message = null, int $statusCode = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $error
        ], $statusCode);
    }
}
