<?php

namespace App\Http\Requests\Checkpoint;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    public function rules(Request $request)
    {
        return [
            'route_id' => 'required|exists:routes,id',
            'arrived_at' => 'required',
            'agency_id' => 'required|exists:agencies,id',
            'order' => ['required', 'numeric', 'gt:0', Rule::unique('checkpoints')->where(function ($q) use ($request) {
                return $q->where('route_id', $request->route_id);
            })],
        ];
    }
}
