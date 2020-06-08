<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceDistributorResource extends JsonResource
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
            "about" => $this->about,
            "phones" => $this->phones,
            "cash" => $this->cash,
            "card" => $this->card,
            "img" => $this->getImgAttribute(),
            "background" => $this->getBackgroundAttribute(),
            "service_lowerst_price" => $this->service_lowerst_price,
            "service_distributor_contacts" => $this->service_distributor_contacts,
            "services" => ServiceResource::collection($this->services),
        ];
    }
}
