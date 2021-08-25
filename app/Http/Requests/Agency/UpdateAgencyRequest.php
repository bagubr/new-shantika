<?php

namespace App\Http\Requests\Agency;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAgencyRequest extends FormRequest
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
            'name' => 'required|unique:agencies,name,' . $this->agency->id,
            'city_id' => 'required|exists:cities,id',
            'code' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'img' => 'nullable|image|max:2048',
        ];
    }
}
