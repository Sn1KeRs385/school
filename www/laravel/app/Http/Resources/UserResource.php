<?php

namespace App\Http\Resources;

use App\Consts\ApartmentAttachStatuses;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $apartments = $this->residents->flatMap(function ($resident) {
            $confirmed = $resident->management_company_id && !$resident->rejected_at ?
            ApartmentAttachStatuses::CONFIRMED :
            ApartmentAttachStatuses::NOT_CONFIRMED;
            $resident->apartments->each(
                function ($apartment) use ($confirmed) {

                    $apartment->pivot['user_id'] = $this->id;
                    //$apartment['description'] = $apartment->pivot->description;
                    $apartment['status'] = $apartment->entrance->house->management_company ? $confirmed : ApartmentAttachStatuses::NOT_FOUND_MANAGEMENT_COMPANY;
                }
            );
            return $resident->apartments;
        });

        return [
            "id" => $this->id,
            "name" => $this->name,
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
            "patronymic" => $this->patronymic,
            "username" => $this->username,
            "email" => $this->email,
            "phone" => $this->phone,
            "borned_at" => $this->borned_at,
            "role_id" => $this->role_id,
            "apartments" => ApartmentResource::collection($apartments),
        ];
    }
}
