<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceSubCategoryResource extends JsonResource
{
// https: //stackoverflow.com/questions/50638257/laravel-5-6-pass-additional-parameters-to-api-resource
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "service_distributors_count" => $this->service_distributors_count,

        ];
    }
}
