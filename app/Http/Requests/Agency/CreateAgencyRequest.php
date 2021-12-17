<?php

namespace App\Http\Requests\Agency;

use Illuminate\Foundation\Http\FormRequest;

class CreateAgencyRequest extends FormRequest
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
            'name' => 'required',
            'city_id' => 'required|exists:cities,id',
            'code' => 'required',
            'address' => 'required',
            'phone' => 'nullable|sometimes',
            'avatar' => 'nullable|image|max:2048',
            'lat' => 'required',
            'lng' => 'required',
        ];
    }
}
