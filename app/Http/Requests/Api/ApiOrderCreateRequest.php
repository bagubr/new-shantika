<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Validator;

class ApiOrderCreateRequest extends ApiRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'route_id'=>'required|numeric',
            'reserve_at'=>'required|date',
            'layout_chair_id'=>'required|array',
            'layout_chair_id.*'=>'numeric',
            'name'=>'required|string',
            'phone'=>'required|string',
            'email'=>'required|email',
            'is_member'=>'sometimes|required|boolean',
            'id_member'=>'sometimes|required|string',
            'is_travel'=>'required|boolean',
            'is_feed'=>'required|boolean'
        ];
    }
}
        