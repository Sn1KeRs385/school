<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            "creator" => $this->creator,
            "order_category_id" => $this->order_category_id,
            "nature_id" => $this->order_nature_id,
            "executor" => $this->executor,
            "media" => $this->media,
            "executor_media" => $this->getExecutorMediaAttribute,
            "order_media" => $this->getOrderMediaAttribute,
            "rating" => $this->rating,
            "desired_at" => $this->desired_at,
            "set_at" => $this->set_at,
            "created_at" => $this->created_at,
            "closed_at" => $this->closed_at,
            "category" => $this->category,
            "general_status_id" => $this->general_status ? $this->general_status->order_status_id : 0,
            "general_status" => $this->general_status ? $this->general_status->status->name : null,
        ];
    }
}
