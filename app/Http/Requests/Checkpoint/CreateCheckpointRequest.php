<?php

namespace App\Http\Requests\Checkpoint;

use Illuminate\Foundation\Http\FormRequest;

class CreateCheckpointRequest extends FormRequest
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
            'route_id' => 'required|exists:routes,id',
            'arrived_at' => 'required',
            'agency_id' => 'required|exists:agencies,id',
            'order' => 'required|numeric'
        ];
    }
}
