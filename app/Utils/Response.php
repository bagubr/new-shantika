<?php
namespace App\Utils;

trait Response {
    public function successResponse($data = [], $message = 'success', $code = 200) {
        $data = array_merge([
            'code'=>$code,
            'success'=>true,
            'message'=>$message,
            'data'=>$data
        ], $data);

        return response($data);
    }

    public function failedResponse($data = [], $message = 'Something is missing out!', $code = 400) {
        $data = array_merge([
            'code'=>$code,
            'success'=>true,
            'message'=>$message,
            'data'=>$data
        ], $data);

        return response($data);
    }

    public function sendSuccessResponse($data = [], $message = 'success', $code = 200) {
        $this->successResponse($data, $message, $code)->send();
        exit;
    }

    public function sendFailedResponse($data = [], $message = 'Something is missing out!', $code = 400) {
        $this->failedResponse($data, $message, $code)->send();
        exit;
    }
}