<?php

namespace App\Http\Requests\UserAgent;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserAgentRequest extends FormRequest
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
            'phone' => 'required',
            'email' => 'required|email',
            'avatar' => 'nullable|image|max:2048',
            'birth_place' => 'nullable',
            'birth' => 'required|date',
            'address' => 'required',
            'gender' => 'required|in:Male,Female',
            'agency_id' => 'required',
            'is_active' => 'required',
        ];
    }
}
