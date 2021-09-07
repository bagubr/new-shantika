<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdminRequest extends FormRequest
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
            'name'      => 'required',
            'email'     => 'required|unique:admins,email',
            'password'  => 'required|min:8|confirmed',
        ];
    }
    public function messages()
    {
        return [
            'password.confirmed' => 'Password Tidak Sama',
        ];
    }
}
