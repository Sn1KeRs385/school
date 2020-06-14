<?php

namespace App\Http\Requests\v2\Api\PaymentTransactions;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function rules()
    {
        $currentYear = now()->year;
        return [
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:50',
            'apartment_id' => 'required|integer|min:1|exists:apartments,id,deleted_at,NULL',
            'year' => "required|integer|min:2010|max:{$currentYear}",
        ];
    }
}
