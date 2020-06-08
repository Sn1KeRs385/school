<?php

namespace App\Http\Requests\Api\Apartments;

use App\ApiModels\Settlement;
use App\ApiModels\Street;
use App\Dadata\DadataService;
use App\Dadata\Endpoints\Suggestions\Address\GetCityByKladrId;
use App\Dadata\Endpoints\Suggestions\Address\GetStreetByKladrId;
use Illuminate\Foundation\Http\FormRequest;

class AttachRequest extends FormRequest
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
            'city_kladr_id' => [
                'required_without:street_kladr_id',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Settlement::whereKladrId($value)->exists()) {
                        $request = new GetCityByKladrId($value);
                        $service = new DadataService($request);
                        $result = $service->send();
                        if (is_null($result) || count($result->suggestions) == 0) {

                            $fail('City not found by kladr_id');
                            return;
                        }
                        $this['city'] = $result->suggestions[0];
                    }
                },
            ],
            'street_kladr_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $street = Street::whereKladrId($value)
                        ->first();
                    if (!$street) {
                        $req = new GetStreetByKladrId($value);
                        $service = new DadataService($req);
                        $result = $service->send();
                        if (is_null($result) || count($result->suggestions) == 0) {

                            $fail('Street not found by kladr_id');
                            return;
                        }
                        $this['dadata'] = $result->suggestions[0];
                    } else {
                        $this['street'] = $street;
                    }
                },
            ],
            'house' => 'required|string',
            'apartment' => 'required|string',
            'description' => 'sometimes|string',
        ];
    }
}
