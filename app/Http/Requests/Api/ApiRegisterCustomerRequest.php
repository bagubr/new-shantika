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
            'name'          =>'required',
            'address'       =>'nullable',
            'phone'         =>'required|unique:users,phone',
            'email'         =>'required|unique:users,email|email:rfc,dns',
            'avatar'        =>'nullable',
            'birth'         =>'required|date',
            'birth_place'   =>'required|string',
            'gender'        =>'required|in:Male,Female',
            'uuid'          =>'required'
        ];
    }
}
        