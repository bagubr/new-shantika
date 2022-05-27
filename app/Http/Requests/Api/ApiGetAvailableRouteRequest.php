<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\TimeClassification;
use Illuminate\Validation\Rule;

class ApiGetAvailableRouteRequest extends ApiRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $time = implode(",",TimeClassification::pluck('id')->toArray());
        return [
            'agency_id'=>'sometimes|numeric',
            'fleet_class_id'=>'sometimes|numeric',
            'agency_departure_id'=>'sometimes|numeric',
            'agency_arrived_id'=>'sometimes|numeric',
            'time_classification_id'=>'sometimes|in:'.$time,
        ];
    }
}
        