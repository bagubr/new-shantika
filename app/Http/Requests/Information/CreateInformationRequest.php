<?php

namespace App\Http\Requests\Information;

use Illuminate\Foundation\Http\FormRequest;

class CreateInformationRequest extends FormRequest
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
            'name' => 'required|unique:informations,name',
            'address' => 'required',
            'description' => 'required',
            'image' => 'required|max:2040|image'
        ];
    }
}
