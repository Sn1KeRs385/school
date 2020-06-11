<?php

namespace App\Http\Requests\Api\Adverts;

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
            'apartment_id' => 'required|integer|exists:apartments,id,deleted_at,NULL',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ];
    }
}