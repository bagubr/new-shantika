<?php

namespace App\Http\Requests\FleetClass;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFleetClassRequest extends FormRequest
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
            'name' => 'required|unique:fleet_classes,name,' . $this->fleetclass->id,
            'price_food' => 'nullable|numeric'
        ];
    }
}
