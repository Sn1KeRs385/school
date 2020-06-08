<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EntranceResource extends JsonResource
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
            "storeys" => $this->storeys,
            "house" => new HouseResource($this->house),
        ];
    }
}
