<?php


namespace App\Http\Services;


use App\Exceptions\CustomException;
use App\Http\Resources\HotelierProfileResource;
use JetBrains\PhpStorm\ArrayShape;

class HotelierProfileService
{
    public function __construct()
    {
        $this->ensureProfileExists();
    }

    /**
     * @param $data
     * @throws CustomException
     */
    public function alter($data)
    {
        $data = filterData($data);
        if (exists($data['license'])) {
            $data['license'] = handleFile('hotel-licenses', $data['license']);
        }
        if (exists($data['national_card'])) {
            $data['national_card'] = handleFile('hotel-national-cards', $data['national_card']);
        }
        currentUser()->profile()->update($data);
    }

    /**
     * @return array
     */
    #[ArrayShape(['profile' => "\App\Http\Resources\HotelierProfileResource"])]
    public function profile(): array
    {
        return [
            'profile' => HotelierProfileResource::make(currentUser()->profile)
        ];
    }

    protected function ensureProfileExists()
    {
        if (!exists(currentUser()->profile)) {
            currentUser()->profile()->create();
        }
    }
}
