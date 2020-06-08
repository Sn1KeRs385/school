<?php

namespace App\Http\Requests\Api\Orders;

use Auth;
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
        $user = Auth::user();
        return $user->confirmed_apartment_resident_pivots()
            ->where('apartment_id', request('apartment_id'))
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
            'is_archive' => 'sometimes|boolean',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1',
            'apartment_id' => 'required|integer|exists:apartments,id',
        ];
    }
}
