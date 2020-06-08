<?php

namespace App\Http\Requests\Admin\ManagementCompanies;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class GetMCbyUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->management_company_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
         return [];
    }
}
