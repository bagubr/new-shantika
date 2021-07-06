<?php

namespace App\Http\Requests;

use App\Utils\Response;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest {
    use Response;

    protected function failedValidation($validator)
    {
        $this->sendFailedResponse(message: $validator->errors()->first());
    }
}