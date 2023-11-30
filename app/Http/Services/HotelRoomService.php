<?php


namespace App\Http\Services;


use App\Exceptions\CustomException;
use App\Models\Hotel;
use App\Models\Room;

class HotelRoomService
{
    /**
     * @param array $data
     * @throws CustomException
     */
    public function createRoom(array $data)
    {
        $data = filterData($data);

        // Ensure hotel exists
        $hotel = Hotel::query()->findOrFail($data['hotel_id']);
        unset($data['hotel_id']);

        // create the room for hotel
        $hotel->rooms()->create($data);
    }

    /**
     * @param array $data
     * @throws CustomException
     */
    public function updateRoom(array $data)
    {
        $data = filterData($data);

        // Ensure room exists
        $room = Room::query()->findOrFail($data['room_id']);
        unset($data['room_id']);

        // update room information
        $room->update($data);
    }
}
