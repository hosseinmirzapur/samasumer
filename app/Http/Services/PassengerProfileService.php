<?php


namespace App\Http\Services;


use App\Exceptions\CustomException;
use App\Http\Resources\PassengerProfileResource;
use JetBrains\PhpStorm\ArrayShape;

class PassengerProfileService
{

    public function __construct()
    {
        $this->ensureProfileExists();
    }

    /**
     * @param array $data
     * @throws CustomException
     */
    public function alter(array $data)
    {
        $data = filterData($data);
        currentUser()->profile()->update($data);
    }

    #[ArrayShape([
        'profile' => "\App\Http\Resources\PassengerProfileResource"
    ])]
    public function profile(): array
    {
        $profile = currentUser()->profile;
        return [
            'profile' => PassengerProfileResource::make($profile)
        ];
    }

    protected function ensureProfileExists()
    {
        if (!exists(currentUser()->profile)) {
            currentUser()->profile()->create();
        }
    }
}
