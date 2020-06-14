<?php

namespace App\Http\Requests\v2\Api\PaymentDocuments;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function rules()
    {
        $now = Carbon::now();
        $minYear = $now->year - 10;
        $maxYear = $now->year;
        return [
            'year' => "required|integer|min:{$minYear}|max:{$maxYear}",
            'apartment_id' => 'required|integer|min:1|exists:apartments,id,deleted_at,NULL',
        ];
    }
}
