<?php

namespace App\Http\Requests;

use App\Rules\MobileNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class PassengerEnterRequest extends FormRequest
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
    #[Pure] #[ArrayShape(['mobile' => "string[]"])] public function rules(): array
    {
        return [
            'mobile' => ['required', new MobileNumberRule]
        ];
    }
}
