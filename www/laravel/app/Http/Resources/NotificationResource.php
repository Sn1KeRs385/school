<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'id' => $this->id,
            'type' => $this->parent_type,
            'title' => $this->parent->title,
            'content' => $this->parent->content,
            'published_at' => $this->parent->published_at,
        ];

    }
}
