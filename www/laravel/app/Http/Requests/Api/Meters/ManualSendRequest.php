<?php

namespace App\Http\Requests\Api\Meters;

use Illuminate\Foundation\Http\FormRequest;
use App\ApiModels\MeterChannel;
use Carbon\Carbon;

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
            'channels.*' => 'required',
            'channels.*.id' => ['required', 'integer', 'min:1',
                function ($attribute, $value, $fail) {
                    $meterChannel = MeterChannel::find($value);
                    if (!$meterChannel) {
                        $fail("Wrong meterChannel id");
                    }
                    $meter = $meterChannel->meter;
                    $house = $meter->location->entrance->house;
                    $allowedMeterTypeIds = $house->allowed_meter_type_ids_to_send_data;
                    $today = Carbon::now()->day;

                    $cond = $today >= $house->send_data_after && $today <= $house->send_data_before;
                    $cond = $cond && in_array($meter->meter_type_id, $allowedMeterTypeIds);
                    if (!$cond) {
                        $fail("wrong time, can't send meter data right now");
                    }
                },
            ],
            'channels.*.value' => 'required|numeric',
        ];
    }
}
