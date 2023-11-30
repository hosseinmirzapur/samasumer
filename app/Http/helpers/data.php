<?php

use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('filterData')) {
    /**
     * @param array $data
     * @return array
     * @throws CustomException
     */
    function filterData(array $data): array
    {
        foreach ($data as $key => $value) {
            if (!exists($value)) {
                unset($data[$key]);
            }
        }
        if (empty($data)) {
            throw new CustomException('INVALID_DATA');
        }
        return $data;

    }
}

if (!function_exists('exists')) {
    /**
     * @param mixed $data
     * @return bool
     */
    function exists(mixed $data): bool
    {
        return isset($data) && $data != '' && $data != null;
    }
}

if (!function_exists('handleFile')) {
    /**
     * @param $path
     * @param $file
     * @return string
     */
    function handleFile($path, $file): string
    {
        $fileName = time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
        Storage::putFileAs($path, $file, $fileName);
        return $path . $fileName;
    }
}

if (!function_exists('generateCode')) {
    /**
     * @param int $digits
     * @return int
     */
    function generateCode(int $digits = 1): int
    {
        $min = pow(10, $digits - 1);
        $max = pow(10, $digits) - 1;
        return mt_rand($min, $max);
    }
}

if (!function_exists('generateReferralCode')) {
    /**
     * Supported types:
     *
     * p for passenger
     *
     * h for hotelier
     *
     * a for agency
     *
     * m for marketer
     *
     * @param string $type
     * @return string
     * @throws Exception
     */
    function generateReferralCode(string $type): string
    {
        $generatedString = Str::lower(Str::random(5));
        return match ($type) {
            'p' => 'p' . $generatedString,
            'h' => 'h' . $generatedString,
            'a' => 'a' . $generatedString,
            'm' => 'm' . $generatedString,
            default => throw new Exception('BACKEND_ERROR'),
        };
    }
}

if (!function_exists('currentUser')) {
    /**
     * @return mixed
     */
    function currentUser(): mixed
    {
        return request()->user();
    }
}

if (!function_exists('deleteFileFromStorage')) {
    /**
     * @param string $path
     * @throws Throwable
     */
    function deleteFileFromStorage(string $path)
    {
        throw_if(!Storage::exists($path), new CustomException('FILE_NOT_FOUND'));
        Storage::delete($path);
    }
}
