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
            'details'=>'required|array',
            'details.*.layout_chair_id'=>'required|array',
            'details.*.layout_chair_id.*'=>'numeric',
            'details.*.name'=>'required|string',
            'details.*.phone'=>'required|string',
            'details.*.email'=>'required|email',
            'details.*.is_member'=>'required|boolean',
            'details.*.id_member'=>'required|string',
            'details.*.is_travel'=>'required|boolean',
            'details.*.is_feed'=>'required|boolean'
        ];
    }
}
        