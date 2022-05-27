<?php

namespace App\Http\Requests\CustomerMenu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerMenuRequest extends FormRequest
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
            'icon' => 'nullable|image|max:2048',
            'order' => 'required|unique:customer_menus,order,' . $this->customer_menu->id
        ];
    }
}
