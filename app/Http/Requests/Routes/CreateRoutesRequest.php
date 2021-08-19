<?php

namespace App\Http\Requests\Routes;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoutesRequest extends FormRequest
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
            // 'name' => 'required|unique:routes,name',
            'area_id' => 'required|exists:areas,id',
            'departure_city_id' => 'required|exists:cities,id',
            'destination_city_id' => 'required|exists:cities,id',
        ];
    }
}
