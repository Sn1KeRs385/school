<?php

namespace App\Http\Requests\Admin\Meters;

use Illuminate\Foundation\Http\FormRequest;
use App\AdminModels\MeterChannel;
use Illuminate\Support\Carbon;

class ManualSendRequest extends FormRequest
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
            'channels' => 'required|array',
            'channels.*' => 'sometimes',
            'channels.*.id' => ['sometimes','integer','min:1',
            function ($attribute, $value, $fail) {
                $meterChannel =  MeterChannel::find($value);
                if (!$meterChannel)
                {
                    $fail(trans('locale.errors.meter_not_found'));
                }
                $house = $meterChannel->meter->location->entrance->house;
                $today = Carbon::now()->day;
                $cond = $today >= $house->send_data_after && $today <= $house->send_data_before;
                if (!$cond) {
                    $fail(trans('locale.errors.meter_wrong_time_send_data'));
                }
            },
        ],
            'channels.*.value' => 'sometimes|numeric',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'channels.required' => 'Показания не переданы!'
        ];
    }
}
