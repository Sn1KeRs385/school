<?php

namespace App\Http\Resources\v2\Api\PaymentDocuments;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceTypePivotResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => ServiceTypeResource::make($this->type),
            'details' => DetailResource::collection($this->details),
        ];
    }
}
