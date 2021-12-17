<?php

namespace App\Http\Requests\ConfigSetting;

use Illuminate\Foundation\Http\FormRequest;

class CreateConfigSettingRequest extends FormRequest
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
            'member' => 'required',
            'travel' => 'required',
            'booking_expired_duration' => 'required',
            'commision' => 'required',
        ];
    }
}
