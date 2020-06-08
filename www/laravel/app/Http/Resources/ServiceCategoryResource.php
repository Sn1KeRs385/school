<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceCategoryResource extends JsonResource
{
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
            "description" => $this->description,
            "color" => $this->color,
            "service_distributors_count" => $this->service_distributors_count, //count($this->sub_categories) != 0 ? 0 : $this->service_distributors_count,
            "img" => $this->getImgAttribute(),
            "sub_categories" => ServiceSubCategoryResource::collection($this->sub_categories),
        ];
    }
}
