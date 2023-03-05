<?php

namespace App\Helpers;

use App\Http\Controllers\Controller;

class ApiDecorator extends Controller
{
    const ERROR = 400;
    const SUCCESS = 200;
    const SERVER_ERROR = 500;

    /**
     * Decorate the response.
     * 
     * @param array $data
     * @param int $status
     * @param string $message
     * @return array
     */
    public static function decorate(mixed $data, int $status, string $message)
    {
        return response()->json(data: [
            'meta' => [
                'status' => $status,
                'message' => $message,
                "version" => "v1",
            ],
            'data' => $data,
        ], status: $status);
    }

    /**
     * Return a success response.
     * 
     * @param array $data
     * @param string $message
     * @return array
     */
    public static function success(mixed $data, string $message = 'success')
    {
        return static::decorate(data: $data, status: static::SUCCESS, message: $message);
    }

    /**
     * Return an error response.
     * 
     * @param array $data
     * @param string $message
     * @return array
     */
    public static function error(mixed $data, int $status = 400, string $message = 'error')
    {
        return static::decorate(data: $data, status: $status, message: $message);
    }
}
