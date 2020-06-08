<?php

namespace App\Http\Requests\Api\ServiceDistributor;

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
            'query' =>'sometimes|string',
            'per_page' => 'sometimes|integer|min:1',
            'page' => 'sometimes|integer|min:1',
            'cat_id'=>'required|exists:service_categories,id',
            'city_kladr_id'=>'required|exists:settlements,kladr_id',
        ];
    }
}
