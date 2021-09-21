<?php

namespace App\Http\Requests\FleetRoutePrice;

use Illuminate\Foundation\Http\FormRequest;

class CreateFleetRoutePriceRequest extends FormRequest
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
            'fleet_route_id' => 'required',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after:start_at',
            'price' => 'required',
            'note' => 'nullable'
        ];
    }
}
