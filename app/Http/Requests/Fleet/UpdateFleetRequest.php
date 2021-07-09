<?php

namespace App\Http\Requests\Fleet;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFleetRequest extends FormRequest
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
            'name' => 'required|unique:fleets,name,' . $this->fleet->id,
            'description' => 'required',
            'fleet_class_id' => 'required|exists:fleet_classes,id',
            'image' => 'image|max:2048'
        ];
    }
}
