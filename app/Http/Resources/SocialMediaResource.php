<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class SocialMediaResource extends JsonResource
{
    #[ArrayShape(['instagram' => "mixed", 'twitter' => "mixed", 'whatsapp' => "mixed", 'facebook' => "mixed"])]
    public function toArray($request): array
    {
        return [
            'instagram' => $this->instagram,
            'twitter' => $this->twitter,
            'whatsapp' => $this->whatsapp,
            'facebook' => $this->facebook
        ];
    }
}
