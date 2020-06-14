<?php

namespace App\Http\Requests\v2\Api\PaymentTransactions;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'personal_account_id' => 'required|integer|min:1|exists:personal_accounts,id,deleted_at,NULL',
            'amount' => 'required|integer|min:100',
        ];
    }
}
