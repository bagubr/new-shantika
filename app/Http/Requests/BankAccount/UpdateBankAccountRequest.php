<?php

namespace App\Http\Requests\BankAccount;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBankAccountRequest extends FormRequest
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
            'bank_name' => 'required|unique:bank_accounts,bank_name,' . $this->bank_account->id,
            'account_name' => 'required',
            'account_number' => 'required',
            'image' => 'nullable|image|max:2048',
        ];
    }
}
