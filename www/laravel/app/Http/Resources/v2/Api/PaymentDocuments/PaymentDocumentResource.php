<?php

namespace App\Http\Resources\v2\Api\PaymentDocuments;

use App\Http\Resources\v2\Api\PaymentDocuments\ExecutorResource;
use App\Http\Resources\v2\Api\PaymentDocuments\ReceiverResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentDocumentResource extends JsonResource
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
            'id' => (int)$this->id,
            'number' => (string)$this->number, // Номер ПД
            'month' => (int)$this->month, // Месяц
            'year' => (int)$this->year, // Год
            'debt' => (string)$this->debt, // Задолженность
            'surcharge' => (string)$this->surcharge, // Пени
            'recalculation' => (string)$this->recalculation, // Перерасчет
            'advance' => (string)$this->advance, // Аванс
            'accrued' => (string)$this->accrued, // Начислено
            'paid' => (string)$this->paid, // Оплачено
            'total_payable' => (string)$this->total_payable, // Итого к оплате
            'pdf_url' => $this->pdf_url, // Ссылка на pdf документ
            'executor' => ExecutorResource::make($this->executor),
            'receiver' => ReceiverResource::make($this->receiver),
            'service_types' => ServiceTypePivotResource::collection($this->serviceTypes),
        ];
    }
}
