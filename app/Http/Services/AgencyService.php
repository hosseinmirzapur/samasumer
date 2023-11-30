<?php


namespace App\Http\Services;


use App\Exceptions\CustomException;
use App\Http\Resources\AgencyResource;
use App\Models\Agency;
use App\Models\Marketer;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

class AgencyService
{

    /**
     * @param array $data
     * @return array
     */
    #[ArrayShape(['exists' => "bool", 'code' => "int", 'token' => "\Ramsey\Uuid\UuidInterface"])]
    public function enter(array $data): array
    {
        $mobile = $data['mobile'];

        $agency = Agency::query()->where('mobile', $mobile)->first();

        // codes
        $code = generateCode(5);
        $token = Str::uuid();

        // "a" stands for passenger
        Cache::put("a.otp.$mobile", $code, 120);
        Cache::put("a.token.$mobile", $token, 120);

        return [
            'exists' => exists($agency),
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
        $sentCode = Cache::get("a.otp.$mobile", 'code');
        $sentToken = Cache::get("a.token.$mobile", 'token');

        // Handle Errors
        throw_if($sentToken == 'token', new CustomException('UNAUTHORIZED'));
        throw_if($token != $sentToken, new CustomException('UNAUTHORIZED'));
        throw_if($sentCode == 'code', new CustomException('OTP_TIMEOUT'));
        throw_if($sentCode != $code, new CustomException('WRONG_OTP'));

        $token = Str::uuid();
        Cache::put("a.v.token.$mobile", $token);

        return [
            'token' => $token
        ];
    }

    /**
     * @param $data
     * @return array
     * @throws CustomException
     * @throws Throwable
     */
    #[ArrayShape(['token' => "mixed"])] public function enterPassword($data): array
    {
        $token = $data['token'];
        $mobile = $data['mobile'];
        $sentToken = Cache::get("a.v.token.$mobile", 'token');

        throw_if($sentToken == 'token', new CustomException('OTP_TIMEOUT'));
        throw_if($sentToken != $token, new CustomException('UNAUTHORIZED'));

        $token = $this->handleAgencyDifferentTypes($data);

        return [
            'token' => $token
        ];
    }

    /**
     * @param $data
     * @return mixed
     * @throws CustomException
     */
    protected function handleAgencyDifferentTypes($data): mixed
    {
        $type = $data['type'];
        return match (Str::lower($type)) {
            'parent' => $this->handleParent($data),
            'child' => $this->handleChild($data),
            'independent' => $this->handleIndependent($data),
            default => throw new CustomException('INVALID_AGENCY_TYPE'),
        };
    }

    /**
     * @param $data
     * @return mixed
     * @throws CustomException
     * @throws Exception
     */
    protected function handleParent($data): mixed
    {
        $agency = Agency::query()->where('mobile', $data['mobile'])->first();
        if (!exists($agency)) {
            $agency = Agency::query()->create([
                'mobile' => $data['mobile'],
                'password' => Hash::make($data['password']),
                'referral_code' => generateReferralCode('a')
            ]);
            $this->handleReferral($data, $agency);
        }
        return $agency->createToken('parent-agency')->plainTextToken;

    }

    /**
     * @param $data
     * @return string
     */
    protected function handleChild($data): string
    {
        /**
         * @var Agency $agency
         */
        $agency = Agency::query()->where('mobile', $data['mobile'])->firstOrFail();
        return $agency->createToken('child-agency')->plainTextToken;
    }

    /**
     * @param $data
     * @return mixed
     * @throws CustomException
     * @throws Exception
     */
    protected function handleIndependent($data): mixed
    {
        $agency = Agency::query()->where('mobile', $data['mobile'])->first();
        if (!exists($agency)) {
            $agency = Agency::query()->create([
                'mobile' => $data['mobile'],
                'password' => Hash::make($data['password']),
                'referral_code' => generateReferralCode('a')
            ]);
            $this->handleReferral($data, $agency);

        }
        return $agency->createToken('independent-agency')->plainTextToken;
    }

    /**
     * @param $data
     * @param $agency
     * @throws CustomException
     */
    protected function handleReferral($data, $agency)
    {
        if (exists($data['referral_code'])) {
            /**
             * @var Marketer $marketer
             */
            $marketer = Marketer::query()->where('referral_code', $data['referral_code'])->first();
            if (!exists($marketer)) {
                throw new CustomException('UNKNOWN_REFERRAL_CODE');
            }

            /**
             * Adding agency to marketer's referral users
             */
            $marketer->agencies()->save($agency);
        }
    }

    /**
     * @param $data
     * @throws Throwable
     */
    public function changePass($data)
    {
        $new_pass = $data['new_pass'];
        $old_pass = $data['old_pass'];

        throw_if(!Hash::check($old_pass, currentUser()->password), new CustomException('WRONG_CREDENTIALS'));

        currentUser()->update([
            'password' => Hash::make($new_pass)
        ]);
    }

    /**
     * @param bool $all
     * @param mixed|string $min
     * @param mixed|string $max
     * @return array
     */
    #[ArrayShape(['agencies' => "\Illuminate\Http\Resources\Json\AnonymousResourceCollection"])]
    public function getChildAgencies(bool $all = true, mixed $min = 'lowest', mixed $max = 'highest'): array
    {
        // Figure out what to return
        $agencies = $this->handleFilteredData($all, $min, $max);

        return [
            'agencies' => AgencyResource::collection($agencies)
        ];
    }

    /**
     * @param $all
     * @param $min
     * @param $max
     * @return Collection|array
     */
    protected function handleFilteredData($all, $min, $max): Collection|array
    {
        // base query
        $agencies = Agency::with(['wallet', 'profile'])
            ->latest()
            ->where('parent_id', currentUser()->id);

        // minimum wallet balance set
        if ($min != 'lowest') {
            $agencies = $agencies->whereHas('wallet', function ($q) use ($min) {
                $q->where('balance', '>=', $min);
            });
        }

        // maximum wallet balance set
        if ($max != 'highest') {
            $agencies = $agencies->whereHas('wallet', function ($q) use ($max) {
                $q->where('balance', '<=', $max);
            });
        }

        // low balance agencies
        if (!$all) {
            $agencies = $agencies->whereHas('wallet', function ($q) {
                $q->where('balance', '<=', '10000');
            });
        }

        return $agencies->get();
    }

    /**
     * @param $data
     * @throws Exception
     */
    public function addChild($data)
    {
        $fullname = $data['fullname'];
        $mobile = $data['mobile'];
        $password = $data['password'];

        /**
         * @var Agency $childAgency
         */
        $childAgency = Agency::query()->create([
            'mobile' => $mobile,
            'password' => $password,
            'referral_code' => generateReferralCode('a'),
            'parent_id' => currentUser()->id,
            'type' => 'CHILD'
        ]);

        // extract firstname and lastname from fullname
        [$name, $lastname] = explode(' ', $fullname);

        $childAgency->profile()->create([
            'name' => $name,
            'lastname' => $lastname
        ]);
    }

    /**
     * @param $query
     * @return array
     */
    #[ArrayShape(['agencies' => "\Illuminate\Http\Resources\Json\AnonymousResourceCollection"])]
    public function searchChildren($query): array
    {
        $agencies = exists($query) ? Agency::with(['wallet', 'profile'])
            ->where('parent_id', currentUser()->id)
            ->whereHas('profile', function ($q) use ($query) {
                $q->where('agency_name', 'LIKE', "%$query%")
                    ->orWhere('name', 'LIKE', "%$query%")
                    ->orWhere('lastname', 'LIKE', "%$query%");
            })
            ->orWhereHas('wallet', function ($q) use ($query) {
                $q->where('balance', 'LIKE', "%$query%");
            })
            ->where('mobile', 'LIKE', "%$query%")
            ->get() : Agency::with(['wallet', 'profile'])->get();
        return [
            'agencies' => AgencyResource::collection($agencies)
        ];
    }

}
