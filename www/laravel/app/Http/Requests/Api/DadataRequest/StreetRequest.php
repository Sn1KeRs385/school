<?php

namespace App\Http\Requests\Api\DadataRequest;

use Illuminate\Foundation\Http\FormRequest;

class StreetRequest extends FormRequest
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
            'query' => 'required|',
            'kladr_id'=>'required|integer',
            'count'=>'integer|between:0,20'
        ];
    }
}
