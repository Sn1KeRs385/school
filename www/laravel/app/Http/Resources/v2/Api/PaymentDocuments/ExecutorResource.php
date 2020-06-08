<?php

namespace App\Http\Resources\v2\Api\PaymentDocuments;

use Illuminate\Http\Resources\Json\JsonResource;

class ExecutorResource extends JsonResource
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
            'name' => (string)$this->name,
        ];
    }
}
