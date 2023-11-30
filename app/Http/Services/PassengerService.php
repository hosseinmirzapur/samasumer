<?php


namespace App\Http\Services;


use App\Exceptions\CustomException;
use App\Models\Passenger;
use App\Models\PassengerReferral;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

class PassengerService
{
    /**
     * @param $data
     * @return array
     */
    #[ArrayShape(['exists' => "bool", 'code' => "int", 'token' => "\Ramsey\Uuid\UuidInterface"])]
    public function handleEnter($data): array
    {
        $mobile = $data['mobile'];

        $passenger = Passenger::query()->where('mobile', $mobile)->first();

        // codes
        $code = generateCode(5);
        $token = Str::uuid();

        // p stands for passenger
        Cache::put("p.otp.$mobile", $code, 120);
        Cache::put("p.token.$mobile", $token, 120);

        return [
            'exists' => exists($passenger),
            'code' => $code,
            'token' => $token
        ];
    }

    /**
     * @throws Throwable
     */
    #[ArrayShape([
        'token' => "\Ramsey\Uuid\UuidInterface"
    ])]
    public function verifyOtp($data): array
    {
        $code = $data['code'];
        $token = $data['token'];
        $mobile = $data['mobile'];
        $sentCode = Cache::get("p.otp.$mobile", 'code');
        $sentToken = Cache::get("p.token.$mobile", 'token');

        // Handle Errors
        throw_if($sentToken == 'token', new CustomException('UNAUTHORIZED'));
        throw_if($token != $sentToken, new CustomException('UNAUTHORIZED'));
        throw_if($sentCode == 'code', new CustomException('OTP_TIMEOUT'));
        throw_if($sentCode != $code, new CustomException('WRONG_OTP'));

        $token = Str::uuid();
        Cache::put("p.v.token.$mobile", $token);

        return [
            'token' => $token
        ];
    }

    /**
     * @param array $data
     * @return array
     * @throws CustomException
     * @throws Throwable
     */
    #[ArrayShape(['token' => "mixed"])]
    public function enterPassword(array $data): array
    {
        $data = filterData($data);
        $token = $data['token'];
        $mobile = $data['mobile'];
        $password = $data['password'];
        $sentToken = Cache::get("p.v.token.$mobile", 'token');

        // Check Authority
        throw_if($token != $sentToken, new CustomException('UNAUTHORIZED'));
        throw_if($sentToken == 'token', new CustomException('UNAUTHORIZED'));
        $passenger = Passenger::query()->where('mobile', $mobile)->first();
        if (exists($passenger)) {
            throw_if(!Hash::check($password, $passenger->password), new CustomException('WRONG_CREDENTIALS'));
        } else {
            $passenger = Passenger::query()->create([
                'mobile' => $mobile,
                'password' => Hash::make($password),
                'referral_code' => generateReferralCode('p')
            ]);
            $this->handleReferral($data, $passenger);
        }
        $sanctum = $passenger->createToken('pToken')->plainTextToken;
        return [
            'token' => $sanctum
        ];
    }

    /**
     * @throws Throwable
     */
    protected function handleReferral($data, $passenger)
    {
        if (exists($data['referral_code'])) {
            $referral_user = Passenger::query()->where('referral_code', $data['referral_code'])->first();
            throw_if(!exists($referral_user), new CustomException('REFERRAL_NOT_FOUND'));
            $data = [
                'passenger_id' => $passenger->id,
                'referral_id' => $referral_user->getAttribute('id')
            ];
            PassengerReferral::query()->updateOrCreate($data, $data);
        }
    }

    /**
     * @param $data
     * @throws Throwable
     */
    public function changePass($data)
    {
        $oldPass = $data['old_pass'];
        $newPass = $data['new_pass'];
        throw_if(!Hash::check($oldPass, currentUser()->password), new CustomException('WRONG_CREDENTIALS'));
        currentUser()->update([
            'password' => Hash::make($newPass)
        ]);
    }
}
