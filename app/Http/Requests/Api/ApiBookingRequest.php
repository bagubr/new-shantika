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
            'fleet_route_id'=>'required',
            'booking_at'=>'required|date',
            'layout_chair_id'=>'required|array',
            'layout_chair_id.*'=>'required|numeric',
            'destination_agency_id'=>'required|numeric',
            'time_classification_id'=>'required'
        ];
    }
}
        