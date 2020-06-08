<?php

namespace App\Http\Resources\v2\Api\PaymentTransactions;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentTransactionCollection extends ResourceCollection
{
    public $collects = PaymentTransactionResource::class;

    public function toArray($request)
    {
        return [
            'items' => $this->collection,
        ];
    }
}
