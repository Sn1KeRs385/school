<?php

namespace App\Http\Requests\Api\ManagementCompanies;

use Illuminate\Foundation\Http\FormRequest;

class GetInfoRequest extends FormRequest
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
            'apartment_id' => 'required|integer|exists:apartments,id'
        ];
    }
}