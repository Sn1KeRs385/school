<?php

namespace App\Http\Requests\Admin\EmergencyService;

use App\AdminModels\ManagementCompany;
use App\AdminModels\Settlement;
use App\Dadata\DadataService;
use App\Dadata\Endpoints\Suggestions\Address\GetCityByKladrId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();

        if ($user->management_company_id) {
            $mc = ManagementCompany::find($user->management_company_id);
            $this['city'] = $mc->settlement;
        }

        return [

            'management_company_id' => [
                'sometimes',
                'nullable',
                function ($attribute, $value, $fail) {
                    $mc = ManagementCompany::find($value);
                    if (!$mc) {
                        $fail('Wrong management_company_id');
                    }
                    $this['city'] = $mc->settlement;
                },
            ],
            'category_id' => 'required|exists:emergency_service_categories,id',
            'name' => 'required|string',
            'comment' => 'sometimes|string',
            'phones' => 'sometimes|array',
            'phones.*.name' => 'sometimes|string',
            'phones.*.phone' => 'sometimes|string',
            'city_kladr_id' => [
                Rule::requiredIf(!Auth::user()->management_company_id && !request('management_company_id')),
                'string',
                function ($attribute, $value, $fail) {
                    if (request('management_company_id')) {
                        return;
                    }
                    $settlement = Settlement::whereKladrId($value)->first();
                    if ($settlement == null) {
                        $req = new GetCityByKladrId($value);
                        $service = new DadataService($req);
                        $result = $service->send();
                        if (is_null($result) || count($result->suggestions) == 0) {
                            $fail('City not found by kladr_id');
                            return;
                        }
                        $this['city_dadata'] = $result->suggestions[0];
                    } else {

                        $this['city'] = $settlement;
                    }

                },

            ],
        ];
    }

    public function fields(){
        return [
            'city_kladr_id' => 'Населенный пункт'
        ];
    }
}
