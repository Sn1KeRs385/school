<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $view = $this->parent->views->first();

        return [
            'id' => $this->id,
            "type" => $this->parent_type,
            'title' => $this->parent->title,
            "published_at" => $this->parent->published_at,
            "viewed_at" => $view ? $view->viewed_at : null,
        ];

    }
}
