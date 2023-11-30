<?php


namespace App\Http\Services;


use App\Exceptions\CustomException;
use App\Http\Resources\HotelResource;
use App\Models\HotelImage;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

class HotelService
{
    public function __construct()
    {
        $this->ensureHotelExists();
    }

    /**
     * @param $data
     * @throws CustomException
     */
    public function alter($data)
    {
        $data = filterData($data);

        // Hotel Image
        $this->handleImage($data);

        // Hotel Facilities
        $this->handleFacilities($data);

        // Alter part
        currentUser()->hotel()->update($data);
    }

    #[ArrayShape(['hotel' => "\App\Http\Resources\HotelResource"])]
    public function getInfo(): array
    {
        $hotel = currentUser()->hotel;
        $hotel->load(['facilities', 'rooms', 'images']);
        return [
            'hotel' => HotelResource::make($hotel)
        ];
    }

    protected function handleImage(&$data)
    {
        if (exists($data['image'])) {
            $data['image'] = handleFile('hotel-images', $data['image']);
            HotelImage::query()->create([
                'hotel_id' => currentUser()->hotel->id,
                'image' => $data['image']
            ]);
        }
    }

    protected function handleFacilities(&$data)
    {
        if (exists($data['facility_ids'])) {
            currentUser()->hotel()->facilities()->sync($data['facility_ids']);
            unset($data['facility_ids']);
        }
    }

    protected function ensureHotelExists()
    {
        if (!exists(currentUser()->hotel)) {
            currentUser()->hotel->create();
        }
    }

    /**
     * @param $image
     * @throws Throwable
     */
    public function deleteImage($image)
    {
        deleteFileFromStorage($image->image);
        $image->delete();
    }

    public function setImageAsMain($image)
    {
        currentUser()->hotel()->update([
            'image' => $image->image
        ]);
    }
}
