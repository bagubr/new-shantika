<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class CreateMemberRequest extends FormRequest
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
            'code_member'       => 'nullable|unique:memberships,code_member',
            'agency_id'         => 'nullable',
            'name'              => 'required',
            'address'           => 'required',
            'phone'             => 'required'
        ];
    }
}
