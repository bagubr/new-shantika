<?php

namespace App\Http\Requests\Restaurant;

use Illuminate\Foundation\Http\FormRequest;

class CreateRestaurantRequest extends FormRequest
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
            'address' => 'required',
            'phone'     => 'required',
            'image'     => 'nullable|image|max:2048',
            'bank_name'     => 'required',
            'bank_owner'     => 'required',
            'bank_account'     => 'required',
            'lat' => 'required',
            'long' => 'required',

        ];
    }
}
