<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use RuntimeException;
use Throwable;

class Geolocation implements Rule
{
    /* Supported "type" values
     * longitude
     * latitude
     */
    protected string $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @param $longitude
     * @return bool|int
     */
    protected function longitude($longitude): bool|int
    {
        return preg_match('/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $longitude);
    }

    /**
     * @param $latitude
     * @return bool|int
     */
    protected function latitude($latitude): bool|int
    {
        return preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', $latitude);
    }

    /**
     * @param $type
     * @throws Throwable
     */
    protected function checkType($type)
    {
        throw_if($type != 'longitude' && $type != 'latitude', new RuntimeException('UNSUPPORTED_TYPE', 503));
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $this->{$this->type}($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Invalid geolocation value.';
    }
}
