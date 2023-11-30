<?php

namespace App\Http\Requests;

use App\Rules\Geolocation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class AlterHotelRequest extends FormRequest
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
            'name' => 'nullable',
            'time_start' => ['nullable', 'date_format:H:i'],
            'time_end' => ['nullable', 'date_format:H:i', 'after:time_start'],
            'has_breakfast' => ['nullable', 'boolean'],
            'has_lunch' => ['nullable', 'boolean'],
            'has_dinner' => ['nullable', 'boolean'],
            'address' => 'nullable',
            'policy' => 'nullable',
            'description' => 'nullable',
            'longitude' => ['nullable', new Geolocation('longitude')],
            'latitude' => ['nullable', new Geolocation('latitude')],
            'image' => ['nullable', File::image()],
            'facility_ids.*' => ['nullable', Rule::exists('facilities', 'id')]
        ];
    }
}
