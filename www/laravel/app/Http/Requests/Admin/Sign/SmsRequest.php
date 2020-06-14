<?php

namespace App\Http\Requests\Admin\Sign;

use Illuminate\Foundation\Http\FormRequest;

class SmsRequest extends FormRequest
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
            'phone' => 'required|string|min:10',
            'sms' => 'required|integer|digits:4',
            'firebase_token' => 'sometimes|nullable|string|min:1',
        ];
    }
}
