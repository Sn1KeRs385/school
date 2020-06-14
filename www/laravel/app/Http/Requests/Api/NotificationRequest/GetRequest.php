<?php

namespace App\Http\Requests\Api\NotificationRequest;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class GetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->confirmed_apartment_resident_pivots()
            ->where('apartment_id', $this->apartment_id)
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'per_page' => 'sometimes|integer|min:1',
            'page' => 'sometimes|integer|min:1',
            'apartment_id' => 'required|integer|exists:apartments,id',
        ];
    }
}
