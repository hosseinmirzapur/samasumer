<?php

namespace App\Http\Requests;

use App\Rules\MobileNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use JetBrains\PhpStorm\ArrayShape;

class MarketerReferralRequest extends FormRequest
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
        $smallData = $this->validate([
            'type' => ['required', Rule::in(['hotelier', 'agency'])]
        ]);

        return $smallData['type'] == 'hotelier' ? $this->hotelierRules() : $this->agencyRules();
    }

    #[ArrayShape([
        'type' => "string",
        'fullname' => "string",
        'license' => "array",
        'national_card' => "array",
        'mobile' => "array",
        'email' => "array",
        'password' => "array"
    ])]
    protected function hotelierRules(): array
    {
        return [
            'type' => 'required',
            'fullname' => 'required',
            'license' => ['required', File::image()],
            'national_card' => ['required', File::image()],
            'mobile' => ['required', new MobileNumberRule],
            'email' => ['required', 'email', Rule::unique('hotelier_profiles', 'email')],
            'password' => ['required', Password::min(8)->letters()->numbers()]
        ];
    }

    #[ArrayShape([
        'type' => "string",
        'mobile' => "array",
        'national_card' => "array",
        'agency_policy' => "array",
        'fullname' => "string",
        'email' => "array",
        'agency_type' => "array",
        'password' => "array"
    ])]
    protected function agencyRules(): array
    {
        return [
            'type' => 'required',
            'mobile' => ['required', new MobileNumberRule],
            'national_card' => ['required', File::image()],
            'agency_policy' => ['required', File::image()],
            'fullname' => 'required',
            'email' => ['required', Rule::unique('marketer_profiles', 'email'), 'email'],
            'agency_type' => ['required', Rule::in(['INDEPENDENT', 'PARENT'])],
            'password' => ['required', Password::min(8)->letters()->numbers()]
        ];
    }
}
