<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use JetBrains\PhpStorm\ArrayShape;

class AlterMarketerProfileRequest extends FormRequest
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
        'email' => "string",
        'address' => "string",
        'card_number' => "string[]",
        'iban' => "string[]",
        'national_card' => "array"
    ])]
    public function rules(): array
    {
        return [
            'name' => 'nullable',
            'lastname' => 'nullable',
            'email' => 'nullable',
            'address' => 'nullable',
            'card_number' => ['nullable', 'min:16'],
            'iban' => ['nullable', 'min:16', 'max:22'],
            'national_card' => ['nullable', File::image()]
        ];
    }
}
