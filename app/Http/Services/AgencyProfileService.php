<?php


namespace App\Http\Services;


use App\Exceptions\CustomException;
use App\Http\Resources\AgencyProfileResource;
use JetBrains\PhpStorm\ArrayShape;

class AgencyProfileService
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
        $this->handleProfileImages($data);
        currentUser()->profile()->update($data);
    }

    /**
     * @return array
     */
    #[ArrayShape(['profile' => "\App\Http\Resources\AgencyProfileResource"])]
    public function getInfo(): array
    {
        $profile = currentUser()->profile;
        return [
            'profile' => AgencyProfileResource::make($profile)
        ];
    }

    protected function ensureProfileExists()
    {
        if (!exists(currentUser()->profile)) {
            currentUser()->profile()->create();
        }
    }

    protected function handleProfileImages(&$data)
    {
        if (exists($data['agency_policy'])) {
            $data['agency_policy'] = handleFile('agency-policies', $data['agency_policy']);
        }
        if (exists($data['national_card'])) {
            $data['national_card'] = handleFile('agency-national-cards', $data['national_card']);
        }
    }
}
