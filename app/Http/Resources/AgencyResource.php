<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgencyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'agency_id' => $this->id,
            'agency_name' => $this->profile->agency_name,
            'owner_name' => $this->profile->name,
            'owner_lastname' => $this->profile->lastname,
            'mobile' => $this->mobile,
            'wallet_balance' => $this->wallet->balance,
            'last_month_income' => $this->lastMonthIncome(),

        ];
    }

    protected function lastMonthIncome(): int
    {
        return 0;
    }
}
