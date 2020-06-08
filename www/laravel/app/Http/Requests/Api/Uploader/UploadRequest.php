<?php

namespace App\Http\Requests\Api\Uploader;

use App\Consts\MediaCollectionNames;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadRequest extends FormRequest
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
            'file' => 'required|mimes:jpeg,png',
            'collection_name' => [
                'required',
                Rule::in(MediaCollectionNames::CONVERT_TO_PNG)],
        ];
    }
}
