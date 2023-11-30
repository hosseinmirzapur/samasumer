<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MarketerProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'address' => $this->address,
            'card_number' => $this->card_number,
            'iban' => $this->iban,
            'national_card_url' => $this->national_card_url
        ];
    }
}
