<?php

namespace App\Http\Requests\Admin\Meters;

use Illuminate\Foundation\Http\FormRequest;

class SendDataRequest extends FormRequest
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
            'value' => 'numeric',
            'time' => 'numeric',
            'id' => 'required|integer|min:1|exists:meter_channels,id',
        ];
    }
}
