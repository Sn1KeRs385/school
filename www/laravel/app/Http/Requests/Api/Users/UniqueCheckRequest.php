<?php

namespace App\Http\Requests\Api\Users;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UniqueCheckRequest extends FormRequest
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
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'patronymic' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'role_id' => 'required|exists:roles,id',
            'management_company_id' => 'sometimes|required|exists:management_companies,id,deleted_at,NULL',
            'username' => [
                'sometimes', 
                'string',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('management_company_id', $this->management_company_id)
                        ->whereNull('deleted_at');
                })],
            'phone' => [
                'required',
                 'string', 
                 'min:10',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('management_company_id', $this->management_company_id)
                        ->whereNull('deleted_at');
                })],
        ];
    }
}
