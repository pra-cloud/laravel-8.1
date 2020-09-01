<?php
namespace App\Traits;

trait ApiResponse
{
    public function processServiceResponse($response)
    {
        if (isset($response['error']))
            return $this->errorResponse($response['message'], $response['data']);

        if (isset($response['success']))
            return $this->successResponse($response['message'], $response['data']);
    }

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
