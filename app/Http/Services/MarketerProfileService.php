<?php


namespace App\Http\Services;


use App\Exceptions\CustomException;
use App\Http\Resources\MarketerProfileResource;
use JetBrains\PhpStorm\ArrayShape;

class MarketerProfileService
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

        $this->handleRequestFile($data);

        currentUser()->profile()->update($data);
    }

    /**
     * @param array $data
     */
    protected function handleRequestFile(array &$data)
    {
        if (exists($data['national_card'])) {
            $data['national_card'] = handleFile('marketer-national-cards', $data['national_card']);
        }
    }

    /**
     * @return array
     */
    #[ArrayShape(['profile' => "\App\Http\Resources\MarketerProfileResource"])]
    public function getInfo(): array
    {
        $profile = currentUser()->profile;

        return [
            'profile' => MarketerProfileResource::make($profile)
        ];

    }

    protected function ensureProfileExists()
    {
        if (!exists(currentUser()->profile)) {
            currentUser()->profile()->create();
        }
    }
}
