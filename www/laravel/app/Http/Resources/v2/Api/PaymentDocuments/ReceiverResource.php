<?php

namespace App\Http\Resources\v2\Api\PaymentDocuments;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceiverResource extends JsonResource
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
            'inn' => $this->inn,
            'kpp' => $this->kpp,
            'bank_name' => $this->bank_name,
            'bik' => $this->bik,
            'operating_account_number' => $this->operating_account_number,
            'correspondent_bank_account' => $this->correspondent_bank_account,
            'address' => $this->address,
        ];
    }
}
