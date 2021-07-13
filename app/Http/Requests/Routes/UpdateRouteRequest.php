<?php

namespace App\Http\Requests\Routes;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRouteRequest extends FormRequest
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
            'name' => 'required|unique:routes,name,' . $this->route->id,
            'fleet_id' => 'required|exists:fleets,id',
            'departure_at' => 'required',
            'arrived_at' => 'required',
            'price' => 'required|numeric'
        ];
    }
}
