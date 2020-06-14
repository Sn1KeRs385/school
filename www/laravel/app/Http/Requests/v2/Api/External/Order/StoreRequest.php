<?php

namespace App\Http\Requests\v2\Api\External\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'order' => 'required|array',
            'order.contact_fio' => 'required|string',
            'order.contact_phone' => 'required|regex:/^\+7\d{10}$/',
            'order.description' => 'required|string',
            'order.order_category_id' => 'required|int|exists:order_categories,id',
            'order.local_status_id' => 'required|int|exists:order_statuses,id',
            'order.order_nature_id' => 'required|int|exists:order_natures,id',

            'resident' => 'required|array',
            'resident.type_id' => 'required|int|exists:resident_types,id',
            'resident.phone' => 'required|regex:/^\+7\d{10}$/',
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
