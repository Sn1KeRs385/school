<?php

namespace App\Http\Resources\v2\Api\PaymentDocuments;

use Illuminate\Http\Resources\Json\JsonResource;


class IndexResource extends JsonResource
{
    public function toArray($request)
    {
        $collection = $this->resource->map(fn($item) => [
            'id' => (int)$item->id,
            'month' => (int)$item->month,
            'year' => (int)$item->year,
            'total_payable' => (string)$item->total_payable,
            'executor' => [
                'name' => $item->executor->name,
            ],
        ]);

        $collection = $collection->groupBy('month');
        for($i = 1; $i <= 12; $i++) {
            if (!isset($collection[$i])) {
                $collection[(string)$i] = [];
            }
        }

        $array = $collection->toArray();
        ksort($array);

        return (object)$array;
    }

    public function jsonSerialize()
    {
        return $this->toArray(request());
    }

}
