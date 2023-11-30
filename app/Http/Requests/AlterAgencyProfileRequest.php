<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class AlterAgencyProfileRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'agency_name' => 'nullable',
            'branch_name' => 'nullable',
            'name' => 'nullable',
            'lastname' => 'nullable',
            'email' => ['nullable', 'email'],
            'address' => 'nullable',
            'card_number' => ['nullable', 'digits:16'],
            'iban' => ['nullable', 'min:16', 'max:22'],
            'agency_policy' => ['nullable', File::image()],
            'national_card' => ['nullable', File::image()],
        ];
    }
}
