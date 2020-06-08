<?php

namespace App\Http\Requests\v2\Api\External\ApartmentResident;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'resident' => 'required|array',
            'resident.type_id' => 'required|int|exists:resident_types,id',
            'resident.phones' => 'required|array',
            'resident.phones.*' => 'required|regex:/^\+7\d{10}$/',
            'resident.email' => 'email|nullable',
            'resident.firstname' => 'required|string',
            'resident.lastname' => 'required|string',
            'resident.patronymic' => 'string|nullable',

            'address' => 'required|array',
            'address.settlement' => 'required|string',
            'address.street' => 'required|string',
            'address.house' => 'required|string',
            'address.apartment' => 'required|string',
        ];
    }
}
