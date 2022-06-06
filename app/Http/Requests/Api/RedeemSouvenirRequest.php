<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;

class RedeemSouvenirRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'agency_id' => 'required|exists:agencies,id',
            'souvenir_id' => 'required|exists:souvenirs,id',
            'quantity' => 'required|integer'
        ];
    }
}
