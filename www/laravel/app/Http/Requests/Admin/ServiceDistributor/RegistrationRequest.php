<?php

namespace App\Http\Requests\Admin\ServiceDistributor;

use App\AdminModels\ManagementCompany;
use App\AdminModels\Settlement;
use App\Dadata\DadataService;
use App\Dadata\Endpoints\Suggestions\Address\GetCityByKladrId;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationRequest extends FormRequest
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
            'city_kladr_id' => [
                Rule::requiredIf(!$user->management_company_id && !request('management_company_id')),
                'string',
                function ($attribute, $value, $fail) use ($user) {

                    if ($user->management_company_id) {
                        $mc = ManagementCompany::find($user->management_company_id);
                        $this['city'] = $mc->settlement;
                        return;
                    }
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
            'name' => 'required|string',
            'service_categories' => 'required|array|min:1',
            'service_categories.*' => 'required|exists:service_categories,id',
            'phones' => 'required|array|min:1',
            'phones.*.name' => 'required|string',
            'phones.*.phone' => 'required|string|regex:/\+7\d{10}/',
            'services' => 'sometimes|array',
            'services.*.name' => 'required|string',
            'services.*.price' => 'required|numeric',

        ];
    }
    public function fields()
    {
        return [
            'phones' => 'Номера телефонов',
            'phones.*.name' => 'Номера телефонов - Название',
            'phones.*.phone' => 'Номера телефонов - Номер телефона',
            'services.*.name' => 'Услуги - Название услуги',
            'services.*.price' => 'Услуги - Цена'
        ];
    }
}
