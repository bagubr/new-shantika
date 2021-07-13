<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Validator;

class ApiLoginRequest extends ApiRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required',
            'fcm_token'=>'string|nullable'
        ];
    }
}
        