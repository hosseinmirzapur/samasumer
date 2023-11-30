<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class SocialMediaRequest extends FormRequest
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
    #[ArrayShape(['facebook' => "string[]", 'whatsapp' => "string[]", 'instagram' => "string[]", 'twitter' => "string[]"])]
    public function rules(): array
    {
        return [
            'facebook' => ['nullable', 'url'],
            'whatsapp' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
            'twitter' => ['nullable', 'url']
        ];
    }
}
