<?php


namespace App\Http\Services;


use App\Exceptions\CustomException;
use App\Models\Marketer;
use App\Models\MarketerReferral;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

class MarketerService
{
    /**
     * @param $data
     * @return array
     */
    #[ArrayShape(['exists' => "bool", 'code' => "int", 'token' => "\Ramsey\Uuid\UuidInterface"])]
    public function handleEnter($data): array
    {
        $mobile = $data['mobile'];
        $marketer = Marketer::query()->where('mobile', $mobile)->first();

        $code = generateCode(5);
        $token = Str::uuid();

        Cache::put("m.otp.$mobile", $code, 120);
        Cache::put("m.token.$mobile", $token, 120);

        return [
            'exists' => exists($marketer),
            'code' => $code,
            'token' => $token
        ];
    }

    /**
     * @param $data
     * @return array
     * @throws Throwable
     */
    #[ArrayShape(['token' => "\Ramsey\Uuid\UuidInterface"])]
    public function verifyOTP($data): array
    {
        $code = $data['code'];
        $token = $data['token'];
        $mobile = $data['mobile'];
        $sentCode = Cache::get("m.otp.$mobile", 'code');
        $sentToken = Cache::get("m.token.$mobile", 'token');

        // Handle Errors
        throw_if($sentToken == 'token', new CustomException('UNAUTHORIZED'));
        throw_if($token != $sentToken, new CustomException('UNAUTHORIZED'));
        throw_if($sentCode == 'code', new CustomException('OTP_TIMEOUT'));
        throw_if($sentCode != $code, new CustomException('WRONG_OTP'));

        $token = Str::uuid();
        Cache::put("m.v.token.$mobile", $token);

        return [
            'token' => $token
        ];
    }

    /**
     * @param $data
     * @return array
     * @throws Throwable
     */
    #[ArrayShape(['token' => "mixed"])]
    public function enterPass($data): array
    {
        $token = $data['token'];
        $mobile = $data['mobile'];
        $password = $data['password'];
        $sentToken = Cache::get("m.v.token.$mobile", 'token');

        // Check Authority
        throw_if($token != $sentToken, new CustomException('UNAUTHORIZED'));
        throw_if($sentToken == 'token', new CustomException('UNAUTHORIZED'));

        $marketer = Marketer::query()->where('mobile', $mobile)->first();

        if (exists($marketer)) {
            throw_if(Hash::check($password, $marketer->password), new CustomException('WRONG_CREDENTIALS'));
        } else {
            $marketer = Marketer::query()->create([
                'mobile' => $mobile,
                'password' => Hash::make($password),
                'referral_code' => generateReferralCode('m')
            ]);
            $this->handleReferral($data, $marketer);
        }
        $sanctum = $marketer->createToken('mToken')->plainTextToken;

        return [
            'token' => $sanctum
        ];
    }

    /**
     * @param $data
     * @param $marketer
     * @throws Throwable
     */
    protected function handleReferral($data, $marketer)
    {
        if (exists($data['referral_code'])) {
            $referral_user = Marketer::query()->where('referral_code', $data['referral_code'])->first();
            throw_if(!exists($referral_user), new CustomException('REFERRAL_NOT_FOUND'));
            $data = [
                'marketer_id' => $marketer->id,
                'child_id' => $referral_user->getAttribute('id'),
                'child_type' => Marketer::class
            ];
            MarketerReferral::query()->updateOrCreate($data, $data);
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
