<?php

namespace App\Http\Requests\Admin\ManagementCompanies;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Helpers\Roles; 
use App\AdminModels\Settlement;
use App\Dadata\DadataService;
use App\Consts\ManagementCompanyTariffs;
use App\Dadata\Endpoints\Suggestions\Address\GetCityByKladrId;

class RegistrationRequest extends FormRequest
{
    public $RequiredIfTariffNotTrial = 'required_unless:tariff,'.ManagementCompanyTariffs::TRIAL;
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
            'city_kladr_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail)   {
                    $settlement = Settlement::whereKladrId($value)->first(); 
                    if (!$settlement) {
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
            'admin_first' => 'required_without:id|string',
            'admin_lastname' => 'required_without:id|string', 
            'admin_patronymic' => 'sometimes|nullable|string',
            'admin_email' => 'required_without:id|email',
            'admin_phone' => 'sometimes|nullable',
            'contract_number' => $this->RequiredIfTariffNotTrial.'|nullable|string',
            'end_access_at' => 'required|date',
            'status' => 'required|exists:management_company_statuses,id',
            'tariff' => 'required|exists:management_company_tariffs,id', 
            'name' => 'required|string',
            'subdomain' => 'required|string|unique:management_companies,subdomain,'.request('id').',id',
            'contract_date' => $this->RequiredIfTariffNotTrial.'|nullable|date',
            'official_name' => $this->RequiredIfTariffNotTrial.'|nullable|string',
            'official_address' => $this->RequiredIfTariffNotTrial.'|nullable',
            'addresses' => $this->RequiredIfTariffNotTrial.'|nullable',
            'ogrn' => $this->RequiredIfTariffNotTrial.'|nullable|string',
            'kpp' => $this->RequiredIfTariffNotTrial.'|nullable|string',
            'rs' => $this->RequiredIfTariffNotTrial.'|nullable|string',
            'ks' => $this->RequiredIfTariffNotTrial.'|nullable|string',
            'inn' => $this->RequiredIfTariffNotTrial.'|nullable|string',
            'bank' => $this->RequiredIfTariffNotTrial.'|nullable|string',            
            'director_firstname' => $this->RequiredIfTariffNotTrial.'|nullable|string',
            'director_lastname' => $this->RequiredIfTariffNotTrial.'|nullable|string',
            'director_patronymic' => $this->RequiredIfTariffNotTrial.'|nullable|string',
            'director_phone' => $this->RequiredIfTariffNotTrial.'|nullable|string',
            'director_email' => $this->RequiredIfTariffNotTrial.'|nullable|string|email',
            'director_position' => $this->RequiredIfTariffNotTrial.'|nullable|string',
            'document' => $this->RequiredIfTariffNotTrial.'|nullable|string',
        ];
    }

    public function errors(){
        return [
            'required_unless' => 'Поле :attribute обязательно для заполнения, когда "Тариф" не "'
                . ManagementCompanyTariffs::ARR_KEY_VALUE[ManagementCompanyTariffs::TRIAL] . '"',
        ];
    }

    public function fields(){
        return [
            'addresses' => 'addresses',
        ];
    }
}
