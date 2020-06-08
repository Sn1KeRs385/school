<?php

namespace App\Http\Requests\Api\TidingRequest;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'archive' => 'sometimes|integer|in:0,1',
            'apartment_id' => 'sometimes|integer|exists:apartments,id'
        ];
    }
}
