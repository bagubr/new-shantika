<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Validator;

class ApiRegisterCustomerRequest extends ApiRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required',
            'phone'=>'required|unique:users,phone',
            'email'=>'required|unique:users,email',
            'avatar'=>'nullable',
            'password'=>'required|min:6|max:16',
            'birth'=>'nullable|date'
        ];
    }
}
        