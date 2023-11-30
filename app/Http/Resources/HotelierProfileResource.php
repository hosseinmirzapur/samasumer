<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelierProfileResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'lastname' => $this->lastname,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'cred_id' => $this->cred_id,
            'birthdate' => $this->birthdate,
            'card_number' => $this->card_number,
            'iban' => $this->iban,
            'license'=> $this->license_url,
            'national_card' => $this->national_card_url,
        ];
    }
}
