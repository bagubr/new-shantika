<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;

class ApiOrderDetailUpdateRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|string',
            'email' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'is_member' => 'sometimes|integer',
            'is_feed' => 'sometimes|integer',
            'is_travel' => 'sometimes|integer',
            'id_member' => 'sometimes|string',
            'note' => 'sometimes|string',
        ];
    }
}
