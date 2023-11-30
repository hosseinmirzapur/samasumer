<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MobileNumberRule implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return preg_match('/(09)[0-9]{9}/', $value) && strlen($value) == 11;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'INVALID_MOBILE';
    }
}
