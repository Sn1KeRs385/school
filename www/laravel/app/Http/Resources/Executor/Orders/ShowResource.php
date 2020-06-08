<?php

namespace App\Http\Resources\Executor\Orders;

use App\Http\Resources\OrderCategoryResource;
use App\Http\Resources\OrderNatureResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowResource extends JsonResource
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
            "contact_fio" => $this->contact_fio,
            "contact_phone" => $this->contact_phone,
            "description" => $this->description,
            "media" => $this->media,
            "rating" => $this->rating,
            "nature_id" => $this->order_nature_id,
            "nature" => new OrderNatureResource($this->nature),
            "desired_at" => $this->desired_at,
            "set_at" => $this->set_at,
            "created_at" => $this->created_at,
            "status_history" => StatusHistoryResource::collection($this->status_history),
            "category" => new OrderCategoryResource($this->category),
            "general_status_id" => $this->general_status ? $this->general_status->order_status_id : 0,
            "general_status" => $this->general_status ? $this->general_status->status->name : null,

        ];
    }
}
