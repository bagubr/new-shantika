<?php

namespace App\Http\Requests\Restaurant;

use Illuminate\Foundation\Http\FormRequest;

class AssignRestaurantUserRequest extends FormRequest
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
<<<<<<< HEAD
            'admin_id' => 'required|exists:admins,id',
            'restaurant_id' => 'required',
            'phone' => 'required'
=======
            'restaurant_id' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'name' => 'required',
            'password' => 'required|confirmed',
>>>>>>> rilisv1
        ];
    }
}
