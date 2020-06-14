<?php

namespace App\Http\Requests\Api\Orders;

use App\ApiModels\Order;
use App\Consts\OrderStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SetStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $order = Order::find(request('id'));
        $user = Auth::user();
        return $order && $order->executor_id == $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:orders,id',
            'status_id' => 'required|integer|in:' . OrderStatuses::IN_PROCESS . ',' . OrderStatuses::FINISHED,
            'temporary_file_ids' => 'sometimes|array|exists:temporary_files,id',
        ];
    }
}
