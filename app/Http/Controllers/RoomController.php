<?php

namespace App\Http\Controllers;


use App\Exceptions\CustomException;
use App\Http\Requests\HotelRoomRequest;
use App\Http\Services\HotelRoomService;
use App\Models\Room;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    protected HotelRoomService $service;

    public function __construct(HotelRoomService $service)
    {
        $this->service = $service;
        $this->middleware('hotelier');
    }

    /**
     * @param HotelRoomRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store(HotelRoomRequest $request): JsonResponse
    {
        $this->service->createRoom($request->validated());
        return successResponse();
    }

    /**
     * @param HotelRoomRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function update(HotelRoomRequest $request): JsonResponse
    {
        $this->service->updateRoom($request->validated());
        return  successResponse();
    }

    /**
     * @param Room $room
     * @return JsonResponse
     */
    public function destroy(Room $room): JsonResponse
    {
        $room->delete();
        return successResponse();
    }
}
