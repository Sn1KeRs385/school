<?php

namespace App\Http\Requests\Admin\ServiceDistributorContact;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckRequest extends FormRequest
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
            'name' => 'required|string',
            'address' => 'required|string',
            //'contacts.*' => 'sometimes|json',
            'schedule' => 'sometimes|array|size:7',
            //'schedule.*' => 'sometimes|json',
            'schedule.*.work_start_at' => 'sometimes|date_format:U|nullable',
            'schedule.*.work_end_at' => 'required_with:schedule.*.work_start_at|date_format:U|after:schedule.*.work_start_at|nullable',
            'schedule.*.lunch_start_at' => 'sometimes|after:schedule.*.work_start_at|date_format:U|nullable',
            'schedule.*.lunch_end_at' => [
                'required_with:schedule.*.lunch_start_at',
                'after:schedule.*.lunch_start_at',
                'before:schedule.*.work_end_at',
                'date_format:U',
                'nullable',
            ]];
    }
}
