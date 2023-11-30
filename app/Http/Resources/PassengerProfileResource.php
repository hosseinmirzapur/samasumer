<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PassengerProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'persian_name' => $this->persian_name,
            'persian_lastname' => $this->persian_lastname,
            'english_name' => $this->english_name,
            'english_lastname' => $this->english_lastname,
            'nat_id' => $this->nat_id,
            'birthdate' => $this->birthdate,
            'email' => $this->email,
            'gender' => $this->gender,
            'card_number' => $this->card_number,
            'iban' => $this->iban,
        ];
    }
}
