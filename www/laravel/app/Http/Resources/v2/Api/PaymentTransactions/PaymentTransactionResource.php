<?php

namespace App\Http\Resources\v2\Api\PaymentTransactions;

use App\Models\v2\PaymentTransaction;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class  PaymentTransactionResource extends JsonResource
{
    public function toArray($request)
    {
        $status = 'processing';
        if(in_array($this->order_status, [PaymentTransaction::NOT_PAYABLE, PaymentTransaction::LOCKED, PaymentTransaction::AUTH_PROCCESSED])) {
            $status = 'processing';
        } else if(in_array($this->order_status, [PaymentTransaction::CANCELED, PaymentTransaction::REFUNDED, PaymentTransaction::AUTH_CANCELED])) {
            $status = 'canceled';
        } else if(in_array($this->order_status, [PaymentTransaction::OK])) {
            $status = 'success';
        }

        return [
            'id' => (int)$this->id,
            'title' => (string)$this->personalAccount->number,
            'amount' => (int)$this->sberbank_amount,
            'status' => (string)$status,
            'type' => [
                'id' => (int) 1,
                'name' => 'Платежная система',
            ],
            'created_at_timestamp' => (int)Carbon::parse($this->created_at)->timestamp,
            'authorized_at' => (int)Carbon::parse($this->sberbank_authorized_at ?? $this->created_at)->timestamp,
        ];
    }
}
