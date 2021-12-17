<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Validator;

class ApiNotificationSettingUpdateRequest extends ApiRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'activity'=>'required|boolean',
            'feature'=>'required|boolean',
            'remember'=>'required|boolean'
        ];
    }
}
        