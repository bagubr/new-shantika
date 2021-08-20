<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Validator;

class ApiCalculateDiscountRequest extends ApiRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_food'=>'required|boolean',
            'is_travel'=>'required|boolean',
            'is_member'=>'required|boolean',
            'seat_count'=>'required|numeric'
        ];
    }
}
        