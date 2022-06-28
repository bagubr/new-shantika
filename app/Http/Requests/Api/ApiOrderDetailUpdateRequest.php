<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;

class ApiOrderDetailUpdateRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
        ];
    }
}
