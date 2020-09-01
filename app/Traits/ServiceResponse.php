<?php
namespace App\Traits;

trait ServiceResponse
{
    public function successResponse($message = null, $data = [])
    {
        $resp['success'] = true;
        $resp['message'] = $message;
        $resp['data'] = $data;

        return $resp;
    }

    public function errorResponse($message = null, $data = [])
    {
        $resp['error'] = true;
        $resp['message'] = $message;
        $resp['data'] = $data;

        return $resp;
    }
}
