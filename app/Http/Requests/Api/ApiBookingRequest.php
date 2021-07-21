<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Validator;

class ApiBookingRequest extends ApiRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
     */
    public function rules()
    {
        return [
            'data'=>'required',
            'data.route_id'=>'required|numeric',
            'data.form'=>'required|array',
            'data.form.*.layout_chair_id'=>'required|array',
            'data.form.*.layout_chair_id.*'=>'numeric',
            'data.form.*.name'=>'required|string',
            'data.form.*.phone'=>'required|string',
            'data.form.*.is_member'=>'required|boolean',
            'data.form.*.id_member'=>'required|string',
            'data.form.*.is_travel'=>'required|boolean',
            'data.form.*.is_lunch'=>'required|boolean'
        ];
    }
}
        