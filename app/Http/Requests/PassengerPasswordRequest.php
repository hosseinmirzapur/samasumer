<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use JetBrains\PhpStorm\ArrayShape;

class PassengerPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape([
        'password' => "array",
        'referral_code' => "string",
        'token' => "string", 'mobile' => "string[]"
    ])]
    public function rules(): array
    {
        return [
            'password' => ['required', Password::min(8)->numbers()->letters()],
            'referral_code' => 'nullable',
            'token' => 'required',
            'mobile' => ['required', 'regex:/(09)[0-9]{9}/', 'digits:11', 'numeric']
        ];
    }
}
