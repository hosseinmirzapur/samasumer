<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use JetBrains\PhpStorm\ArrayShape;

class HotelierProfileRequest extends FormRequest
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
        'name' => "string",
        'lastname' => "string",
        'email' => "array",
        'cred_id' => "string",
        'birthdate' => "string[]",
        'card_number' => "string[]",
        'iban' => "string[]",
        'license' => "array",
        'national_card' => "array"
    ])]
    public function rules(): array
    {
        return [
            'name' => 'nullable',
            'lastname' => 'nullable',
            'email' => ['nullable', 'email', Rule::unique('hotelier_profiles', 'email')],
            'cred_id' => 'nullable',
            'birthdate' => ['nullable', 'date'],
            'card_number' => ['nullable', 'digits:16'],
            'iban' => ['nullable', 'min:16', 'max:22'],
            'license'=> ['nullable', File::image()],
            'national_card' => ['nullable', File::image()],
        ];
    }
}
