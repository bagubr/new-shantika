<?php

namespace App\Http\Requests\Layout;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLayoutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required',
            'col'=>'required|numeric',
            'row'=>'required|numeric',
            'space_indexes'=>'required|array',
            'toilet_indexes'=>'required|array',
            'door_indexes'=>'required|array',
            'chair_indexes'=>'required|array',
            'note'=>'string|nullable',
        ];
    }
}
