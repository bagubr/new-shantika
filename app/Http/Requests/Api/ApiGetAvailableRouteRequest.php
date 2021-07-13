<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Validator;

class ApiGetAvailableRouteRequest extends ApiRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fleet_class_id'=>'required',
            'agency_id'=>'required',
            'time_start'=>'required',
            'time_end'=>'required'
        ];
    }
}
        