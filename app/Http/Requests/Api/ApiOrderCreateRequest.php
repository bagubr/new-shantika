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
            'fleet_route_id'=>'required|numeric',
            'departure_agency_id'=>'required|numeric',
            'destination_agency_id'=>'required|numeric',
            'time_classification_id'=>'required|numeric',
            'reserve_at'=>'required|date',
            'layout_chair_id'=>'required|array',
            'layout_chair_id.*'=>'numeric',
            'name'=>'required|string',
            'phone'=>'required|string',
            'email'=>'nullable|email:dns,rfc',
            'is_member'=>'sometimes|required|boolean',
            'id_member'=>'nullable|string',
            'is_travel'=>'sometimes|required|boolean',
            'is_feed'=>'sometimes|required|boolean',
            'promo_id'=>'sometimes|numeric',
            'note'=>'sometimes|string',
        ];
    }
}
        