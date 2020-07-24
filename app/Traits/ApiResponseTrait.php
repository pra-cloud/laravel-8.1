<?php
namespace App\Traits;

trait ApiResponseTrait
{
    public function successResponse($message = null, $data = [])
    {
        $resp['success'] = true;

        if (!is_null($message)) {
            $resp['message'] = $message;
        }

        if (!empty($data)) {
            $resp['data'] = $data;
        }
        
        return response()->json($resp);
    }

    public function errorResponse($message = null, $data = [])
    {
        $resp['error'] = true;

        if (!is_null($message)) {
            $resp['message'] = $message;
        }

        if (!empty($data)) {
            $resp['data'] = $data;
        }

        return response()->json($resp, 500);
    }
}