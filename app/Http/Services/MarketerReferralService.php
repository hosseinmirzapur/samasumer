<?php


namespace App\Http\Services;


use App\Exceptions\CustomException;
use App\Http\Resources\ReferralAgencyResource;
use App\Http\Resources\ReferralHotelierResource;
use App\Models\Agency;
use App\Models\Hotelier;
use App\Models\MarketerReferral;
use Exception;
use Illuminate\Support\Facades\Hash;
use JetBrains\PhpStorm\ArrayShape;

class MarketerReferralService
{
    /**
     * @param array $data
     * @throws CustomException
     */
    public function handleHotelier(array $data)
    {
        $mobile = $data['mobile'];
        if (exists(Hotelier::query()->where('mobile', $mobile)->first())) {
            throw new CustomException('USER_EXISTS');
        }
        /**
         * Creating the hotelier account
         *
         * @var Hotelier $hotelier
         */
        $hotelier = Hotelier::query()->create([
            'mobile' => $mobile,
            'password' => Hash::make($data['password'])
        ]);
        [$firstname, $lastname] = explode(' ', $data['fullname']);

        /**
         * Creating the hotelier profile
         */
        $hotelier->profile()->create([
            'name' => $firstname,
            'lastname' => $lastname,
            'license' => handleFile('hotel-licenses', $data['license']),
            'national_card' => handleFile('hotel-national-cards', $data['national_card']),
            'email' => $data['email']
        ]);

        /**
         * Adding the hotel to marketer's referred users
         */
        MarketerReferral::query()->create([
            'marketer_id' => currentUser()->id,
            'child_id' => $hotelier->getAttribute('id'),
            'child_type' => Hotelier::class
        ]);
    }

    /**
     * @param array $data
     * @throws CustomException
     * @throws Exception
     */
    public function handleAgency(array $data)
    {
        $mobile = $data['mobile'];
        if (exists(Agency::query()->where('mobile', $mobile)->first())) {
            throw new CustomException('USER_EXISTS');
        }
        /**
         * Creating the agency
         *
         * @var Agency $agency
         */
        $agency = Agency::query()->create([
            'mobile' => $data['mobile'],
            'password' => $data['password'],
            'referral_code' => generateReferralCode('a'),
            'type' => $data['agency_type']
        ]);
        [$firstname, $lastname] = explode(' ', $data['fullname']);

        /**
         * Creating agency profile
         */
        $agency->profile()->create([
            'name' => $firstname,
            'lastname' => $lastname,
            'email' => $data['email'],
            'national_card' => handleFile('agency-national-cards', $data['national_card']),
            'agency_policy' => handleFile('agency-policies', $data['agency_policy'])
        ]);

        /**
         * Adding the agency to marketer's referred users
         */
        MarketerReferral::query()->create([
            'marketer_id' => currentUser()->id,
            'child_id' => $agency->getAttribute('id'),
            'child_type' => Agency::class
        ]);
    }

    #[ArrayShape([
        'agencies' => "\Illuminate\Http\Resources\Json\AnonymousResourceCollection",
        'hoteliers' => "\Illuminate\Http\Resources\Json\AnonymousResourceCollection"
    ])]
    public function getList(): array
    {
        $referredAgencies = currentUser()->agencies()->get();
        $referredHoteliers = currentUser()->hoteliers()->get();

        return [
            'agencies' => ReferralAgencyResource::collection($referredAgencies),
            'hoteliers' => ReferralHotelierResource::collection($referredHoteliers)
        ];
    }
}
