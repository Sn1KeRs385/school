<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "house" => $this->house,
            "send_data_after" => $this->send_data_after,
            "send_data_before" => $this->send_data_before,
            "street" => $this->street,
            "is_meter_data_pass" => $this->is_meter_data_pass,
            'allowed_meter_types_to_send_data' => $this->allowed_meter_type_ids_to_send_data,
            "management_company" => $this->management_company ? [
                "name" => $this->management_company->name,
                "official_name" => $this->management_company->official_name,
                'is_registered' => $this->management_company->is_registered,
                'reforma_gkh' => $this->management_company->reforma_gkh_management_company
            ] : null,
        ];
    }
}
