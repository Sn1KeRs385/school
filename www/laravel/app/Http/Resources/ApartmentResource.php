<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentResource extends JsonResource
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
            "number" => $this->number,
            "description" => $this->description,
            "pivot" => $this->pivot,
            "status" => $this->status,
            "entrance" => new EntranceResource($this->entrance),
        ];
    }
}
