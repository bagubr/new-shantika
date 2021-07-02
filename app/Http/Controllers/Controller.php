<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function successResponse($data = [], $message = 'success', $code = 200) {
        $data = array_merge([
            'code'=>$code,
            'success'=>true,
            'message'=>$message
        ], $data);

        return response($data);
    }

    protected function failedResponse($data = [], $message = 'Something is missing out!', $code = 400) {
        $data = array_merge([
            'code'=>$code,
            'success'=>false,
            'message'=>$message
        ], $data);

        return response($data);
    }

    protected function sendSuccessResponse($data = [], $message = 'Something is missing out!', $code = 400) {
        $this->successResponse($data, $message, $code)->send();
        exit;
    }

    protected function sendFailedResponse($data = [], $message = 'Something is missing out!', $code = 400) {
        $this->failedResponse($data, $message, $code)->send();
        exit;
    }
}
