<?php

namespace App\Http\Resources\v2\Api\PaymentDocuments;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowResource extends JsonResource
{
    public function toArray($request)
    {
        $user = Auth::user();

        $resident = $this->personalAccount
            ->apartment
            ->residents()
            ->where('residents.user_id', $user->id)
            ->first();

        $fullname = collect([
            $resident->lastname ?? null,
            $resident->firstname ?? null,
            $resident->patronymic ?? null,
        ])
            ->filter()
            ->join(' ');

        $address = collect([
            $this->personalAccount->apartment->entrance->house->street->settlement->name ?? null,
            $this->personalAccount->apartment->entrance->house->street->name ?? null,
            $this->personalAccount->apartment->entrance->house->house ?? null,
            $this->personalAccount->apartment->number ?? null,
        ])
            ->filter()
            ->join(', ');

        $mc = $resident->managementCompany;

        $canPayableThroughSberbank = false;
        if($mc) {
            $canPayableThroughSberbank = $mc->sberbank_active
                && !is_null($mc->sberbank_username)
                && !is_null($mc->sberbank_password)
                && $this->is_belongs_to_mc;
        }

        return [
            'id' => $this->id,
            'month' => $this->month,
            'year' => $this->year,
            'debt' => $this->debt,
            'surcharge' => $this->surcharge,
            'recalculation' => $this->recalculation,
            'advance' => $this->advance,
            'accrued' => $this->accrued,
            'paid' => $this->paid,
            'total_payable' => $this->total_payable,
            'pdf_url' => $this->pdf_url,
            'can_payable_through_sberbank' => (bool)$canPayableThroughSberbank,
            'personal_account' => [
                'id' => $this->personalAccount->id,
                'number' => $this->personalAccount->number,
                'fullname' => $fullname,
                'address' => $address,
            ],
            'recipient' => [
                'name' => $this->recipient->name,
                'inn' => $this->recipient->inn,
                'bank_name' => $this->recipient->bank_name,
                'bik' => $this->recipient->bik,
                'operating_account_number' => $this->recipient->operating_account_number,
                'correspondent_bank_account' => $this->recipient->correspondent_bank_account,
                'address' => $this->recipient->address,
            ],
            'service_types' => $this->serviceTypePivots->map(fn($serviceTypePivot) => [
                'type' => [
                    'name' => $serviceTypePivot->serviceType->name,
                ],
                'details' => $serviceTypePivot->details->map(fn($detail) => [
                    'name' => $detail->name,
                    'payment_total' => $detail->payment_total,
                ]),
            ]),
        ];
    }
}
