<?php

namespace App\Http\Requests;

use App\Models\PassengerProfile;
use App\Rules\NationalCodeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlterProfileRequest extends FormRequest
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
    public function rules()
    {
        return [
            'persian_name' => 'nullable',
            'persian_lastname' => 'nullable',
            'english_name' => 'nullable',
            'english_lastname' => 'nullable',
            'nat_id' => ['nullable', new NationalCodeRule],
            'birthdate' => ['nullable', 'date'],
            'email' => ['nullable', 'email'],
            'gender' => ['nullable', Rule::in(PassengerProfile::GENDERS)],
            'card_number' => ['nullable', 'digits:11'],
            'iban' => ['nullable', 'min:16', 'max:24']
        ];
    }
}
