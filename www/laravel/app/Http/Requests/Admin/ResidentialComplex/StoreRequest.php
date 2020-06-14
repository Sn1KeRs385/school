<?php

namespace App\Http\Requests\Admin\ResidentialComplex;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Illuminate\Validation\Rule;

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
        $management_company_id = Auth::user()->management_company_id;

        return  [
            'name' => 'required|string',
            'management_company_id' => [
                Rule::requiredIf(!$management_company_id),
                'integer',
                'exists:management_companies,id'
            ],
        ];
    }
}
