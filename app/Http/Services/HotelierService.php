<?php


namespace App\Http\Services;


use App\Exceptions\CustomException;
use App\Models\Hotelier;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

class HotelierService
{
    #[ArrayShape(['exists' => "bool", 'code' => "int", 'token' => "\Ramsey\Uuid\UuidInterface"])]
    public function handleEnter($data): array
    {
        $mobile = $data['mobile'];
        $hotelier = Hotelier::query()->where('mobile', $mobile)->first();

        $code = generateCode(5);
        $token = Str::uuid();

        Cache::put("h.otp.$mobile", $code, 120);
        Cache::put("h.token.$mobile", $token, 120);

        return [
            'exists' => exists($hotelier),
            'code' => $code,
            'token' => $token
        ];
    }

    /**
     * @param array $data
     * @return array
     * @throws Throwable
     */
    #[ArrayShape(['token' => "\Ramsey\Uuid\UuidInterface"])]
    public function verifyOtp(array $data): array
    {
        $code = $data['code'];
        $token = $data['token'];
        $mobile = $data['mobile'];
        $sentCode = Cache::get("h.otp.$mobile", 'code');
        $sentToken = Cache::get("h.token.$mobile", 'token');

        // Handle Errors
        throw_if($sentToken == 'token', new CustomException('UNAUTHORIZED'));
        throw_if($token != $sentToken, new CustomException('UNAUTHORIZED'));
        throw_if($sentCode == 'code', new CustomException('OTP_TIMEOUT'));
        throw_if($sentCode != $code, new CustomException('WRONG_OTP'));

        $token = Str::uuid();
        Cache::put("h.v.token.$mobile", $token);

        return [
            'token' => $token
        ];
    }

    /**
     * @param array $data
     * @return array
     * @throws Throwable
     */
    #[ArrayShape(['token' => "mixed"])] public function enterPassword(array $data): array
    {
        $token = $data['token'];
        $mobile = $data['mobile'];
        $password = $data['password'];
        $sentToken = Cache::get("h.v.token.$mobile", 'token');

        // Check Authority
        throw_if($token != $sentToken, new CustomException('UNAUTHORIZED'));
        throw_if($sentToken == 'token', new CustomException('UNAUTHORIZED'));

        $hotelier = Hotelier::query()->where('mobile', $mobile)->first();

        if (exists($hotelier)) {
            throw_if(Hash::check($password, $hotelier->password), new CustomException('WRONG_CREDENTIALS'));
        } else {
            $hotelier = Hotelier::query()->create([
                'mobile' => $mobile,
                'password' => Hash::make($password)
            ]);
        }
        $sanctum = $hotelier->createToken('hToken')->plainTextToken;
        return [
            'token' => $sanctum
        ];
    }

    /**
     * @param array $data
     * @throws Throwable
     */
    public function changePass(array $data)
    {
        $oldPass = $data['old_pass'];
        $newPass = $data['new_pass'];
        throw_if(!Hash::check($oldPass, currentUser()->password), new CustomException('WRONG_CREDENTIALS'));
        currentUser()->update([
            'password' => Hash::make($newPass)
        ]);
    }
}
