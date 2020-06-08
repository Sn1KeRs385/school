<?php

namespace App\Http\Resources\v2\Api\PaymentDocuments;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailResource extends JsonResource
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
            'name' => $this->name,
            'individual_volume' => $this->individual_volume,
            'total_volume' => $this->total_volume,
            'tariff' => $this->tariff,
            'individual_payment_amount' => $this->individual_payment_amount,
            'common_payment_amount' => $this->common_payment_amount,
            'total_charges' => $this->total_charges,
            'recalculation' => $this->recalculation,
            'privileges' => $this->privileges,
            'payment_total' => $this->payment_total,
            'payment_individual' => $this->payment_individual,
            'payment_common' => $this->payment_common,
            'unit' => UnitResource::make($this->unit),
        ];
    }
}
