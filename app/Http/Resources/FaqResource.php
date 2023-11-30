<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'question' => $this->getTranslations('question', [app()->getLocale()]),
            'answer' => $this->getTranslations('answer', [app()->getLocale()]),
            'type' => $this->type,
            'id' => $this->id
        ];
    }
}
