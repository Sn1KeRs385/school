<?php

namespace App\Http\Requests\Api\Apartments;

use App\ApiModels\ApartmentResident;
use App\ApiModels\Resident;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ChangeDescriptionRequest extends FormRequest
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
            'description' => 'required|string|min:1',
            'apartment_id' => [
                'required',
                'integer',
                'exists:apartments,id,deleted_at,NULL',
                function ($attribute, $value, $fail) {
                    $residentsQuery = Resident::where('user_id', Auth::user()->id)
                        ->select('id');
                    $apartment_resident = ApartmentResident::where('apartment_id', $value)
                        ->whereIn('resident_id', $residentsQuery)
                        ->first();
                    if(!$apartment_resident){
                        $fail('Apartment for user not found');
                        return;
                    }
                },
            ],
        ];
    }
}
