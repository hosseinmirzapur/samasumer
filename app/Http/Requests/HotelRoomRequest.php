<?php

namespace App\Http\Requests;

use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HotelRoomRequest extends FormRequest
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
        return $this->isMethod('post') ? $this->postRules() : $this->putRules();
    }

    /**
     * @return array
     */
    public function postRules(): array
    {
        return [
            'type' => ['required', Rule::in(Room::TYPE)],
            'title' => 'required',
            'room_count' => ['required', 'min:0', 'integer'],
            'bed_count' => ['required', 'min:0', 'integer'],
            'capacity' => ['required', 'min:0', 'integer'],
            'daily_price' => ['required', 'min:0', 'integer'],
            'refund_policy' => 'required',
            'description' => 'nullable',
            'discount' => ['nullable', 'min:0'],
            'available_count' => ['required', 'min:0', 'integer'],
            'hotel_id' => ['required', Rule::exists('hotels', 'id')]
        ];
    }

    /**
     * @return array
     */
    public function putRules(): array
    {
        return [
            'type' => ['nullable', Rule::in(Room::TYPE)],
            'title' => 'nullable',
            'room_count' => ['nullable', 'min:0', 'integer'],
            'bed_count' => ['nullable', 'min:0', 'integer'],
            'capacity' => ['nullable', 'min:0', 'integer'],
            'daily_price' => ['nullable', 'min:0', 'integer'],
            'refund_policy' => 'nullable',
            'description' => 'nullable',
            'discount' => ['nullable', 'min:0'],
            'available_count' => ['nullable', 'min:0', 'integer'],
            'room_id' => ['required', Rule::exists('rooms', 'id')]
        ];
    }
}
