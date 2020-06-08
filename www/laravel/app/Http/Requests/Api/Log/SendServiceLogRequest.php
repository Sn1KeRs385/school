<?php

namespace App\Http\Requests\Api\Log;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Consts\ServiceLogActions; 

class SendServiceLogRequest extends FormRequest
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
            'action_id' => [
                'required', 
                Rule::in(array_keys(ServiceLogActions::ARR_KEY_VALUE)),
            ],
            'distributor_id'=>'required|exists:service_distributors,id',
            'comment'=>'sometimes|string'
        ];
    }
}
