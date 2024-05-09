<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendResponse($result, $message="", $code = 200)
    {
        $response = [
            'data'    => $result,
            'message' => $message,
            'code' =>$code
        ];
        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return string
     */

    public function sendError($message, $error = [], $code = 400)
    {
        $response = [
            'message' => $message,
            'errors' => $error,
            'code' => $code,
            
        ];
        
        return response()->json($response, 500);
    }

}
