<?php

namespace App\Http\Requests\v2\Api\PaymentTransactions;

use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => 'required|integer|min:1|exists:payment_transactions,id',
        ];
    }
}
