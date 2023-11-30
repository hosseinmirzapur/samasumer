<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgencyProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'agency_name' => $this->agency_name,
            'branch_name' => $this->brnach_name,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'address' => $this->address,
            'card_number' => $this->card_number,
            'iban' => $this->iban,
            'agency_policy_url' => $this->agency_policy_url,
            'national_card_url' => $this->national_card_url
        ];
    }
}
