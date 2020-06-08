<?php

namespace App\Http\Requests\Api\Meters;

use Illuminate\Foundation\Http\FormRequest;

class SendDataByEmailRequest extends FormRequest
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
            'apartment_id' => 'required|integer|min:1|exists:apartments,id',
            'contact_email' => 'sometimes|email',
            'contact_phone' =>'sometimes|string|min:10',
            'description' => 'required|string|min:1|max:500',
        ];
    }
}
