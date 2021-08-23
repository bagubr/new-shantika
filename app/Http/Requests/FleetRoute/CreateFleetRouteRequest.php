<?php

namespace App\Http\Requests\FleetRoute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CreateFleetRouteRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'fleet_id' => ['required', Rule::unique('fleet_routes')->where(function ($q) use ($request) {
                return $q->where('route_id', $request->route_id);
            })],
            'route_id' => 'required|exists:routes,id',
            'price' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'fleet_id.unique' => 'Armada Sudah Digunakan',
        ];
    }
}
