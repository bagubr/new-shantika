<?php

namespace App\Http\Requests\Layout;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
            'space_indexes'=>'nullable|array',
            'toilet_indexes'=>'nullable|array',
            'door_indexes'=>'nullable|array',
            'chair_indexes'=>'required|array',
            'note'=>'string|nullable',
        ];
    }
}
