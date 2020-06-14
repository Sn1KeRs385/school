<?php

namespace App\Http\Requests\Api\ServiceCategory;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        
        return request('apartment_id')? 
                Auth::user()->apartment_resident_pivots()
                ->where('apartment_id',request('apartment_id'))
                ->exists()
                : true ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'query' => 'sometimes|string',
            'per_page' => 'sometimes|integer|min:1',
            'page' => 'sometimes|integer|min:1',
            'city_kladr_id' => 'required|exists:settlements,kladr_id',
            'apartment_id' => 'nullable|exists:apartments,id',
        ];
    }
}
