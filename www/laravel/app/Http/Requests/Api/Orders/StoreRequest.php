<?php

namespace App\Http\Requests\Api\Orders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
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
            'desired_at' => 'sometimes|date_format:U',
            'temporary_file_ids' => 'sometimes|array|exists:temporary_files,id',
            'apartment_id' => 'required|integer|min:1|exists:apartments,id',
        ];

    }
}
