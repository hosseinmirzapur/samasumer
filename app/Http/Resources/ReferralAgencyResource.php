<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReferralAgencyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'agency_name' => $this->profile->agency_name,
            'name' => $this->profile->name,
            'lastname' => $this->profile->lastname,
            'mobile' => $this->mobile,
            'registered_at' => $this->created_at,
            'marketer_profit' => $this->getMarketerProfit()
        ];
    }

    protected function getMarketerProfit(): int
    {
        return 0;
    }
}
