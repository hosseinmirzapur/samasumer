<?php


namespace App\Http\Services;


use App\Http\Resources\SocialMediaResource;
use App\Models\SocialMedia;
use JetBrains\PhpStorm\ArrayShape;

class SocialMediaService
{
    public function __construct()
    {
        $this->ensureSocialInfoExists();
    }

    /**
     * @return array
     */
    #[ArrayShape(['info' => "\App\Http\Resources\SocialMediaResource"])]
    public function getInfo(): array
    {
        $socialMedia = SocialMedia::query()->get()->last();
        return [
            'info' => SocialMediaResource::make($socialMedia)
        ];
    }

    /**
     * @param array $data
     */
    public function setInfo(array $data)
    {
        SocialMedia::query()->update($data);
    }

    protected function ensureSocialInfoExists()
    {
        if (!exists(SocialMedia::query()->first())) {
            SocialMedia::query()->create();
        }
    }
}
