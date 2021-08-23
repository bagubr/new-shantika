<?php

namespace App\Http\Requests\FleetRoute;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFleetRouteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fleet_id' => 'required',
            'route_id' => 'required',
            'price' => 'required',
            'is_active' => 'required'
        ];
    }
}
