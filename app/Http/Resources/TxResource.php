<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TxResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'code' => $this->code,
            'createdAt' => $this->created_at,
            'gateway' => $this->gateway,
            'tx_type' => $this->payment_type,
            'amount' => $this->amount,
            'description' => $this->description
        ];
    }
}
