<?php

namespace App\Http\Resources\Executor\Orders;

use App\Helpers\ROLES;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $creator_name = '';
        if ($this->resident_id) {
            $creator_name = $this->resident;
            $creator_name = "{$creator_name->lastname} {$creator_name->firstname} {$creator_name->patronymic}";
        } else {
            $creator_name = $this->user;
            if ($creator_name->role_id == ROLES::IS_UK_EXECUTOR) {
                $creator_name = "{$creator_name->lastname} {$creator_name->firstname} {$creator_name->patronymic}";
            } else {
                $creator_name = "Диспетчер";
            }
        }

        return [
            "id" => $this->id,
            "status" => $this->status->name,
            "comment" => $this->comment,
            "media" => $this->media,
            "created_at" => $this->created_at,
            "status_id" => $this->order_status_id,
            "creator_name" =>  $creator_name, 

        ];
    }
}
