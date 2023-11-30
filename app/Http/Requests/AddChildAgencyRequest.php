<?php

namespace App\Http\Requests;

use App\Rules\MobileNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use JetBrains\PhpStorm\ArrayShape;

class AddChildAgencyRequest extends FormRequest
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
    #[ArrayShape(['fullname' => "string", 'mobile' => "array", 'password' => "array"])]
    public function rules(): array
    {
        return [
            'fullname' => 'required',
            'mobile' => ['required', Rule::unique('agencies', 'id'), new MobileNumberRule],
            'password' => ['required', Password::min(8)->numbers()->letters()]
        ];
    }
}
