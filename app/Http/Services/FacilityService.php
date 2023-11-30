<?php


namespace App\Http\Services;


use App\Exceptions\CustomException;
use App\Http\Resources\FacilityResource;
use App\Models\Facility;
use JetBrains\PhpStorm\ArrayShape;

class FacilityService
{
    #[ArrayShape(['facilities' => "\Illuminate\Http\Resources\Json\AnonymousResourceCollection"])]
    public function getAll(): array
    {
        $facilities = Facility::query()->get();

        return [
            'facilities' => FacilityResource::collection($facilities)
        ];
    }

    #[ArrayShape(['facility' => "\App\Http\Resources\FacilityResource"])]
    public function getOne($facility): array
    {
        return [
            'facility' => FacilityResource::make($facility)
        ];
    }

    /**
     * @param $data
     */
    public function handleStore($data)
    {
        $icon = handleFile('facility-icons', $data['icon']);
        $title = $data['title'];
        Facility::query()->updateOrCreate(['title' => $title], [
            'icon' => $icon,
            'title' => $title
        ]);
    }

    /**
     * @param $facility
     * @param $data
     * @throws CustomException
     */
    public function handleUpdate($facility, $data)
    {
        $data = filterData($data);

        if (exists($data['icon'])) {
            $data['icon'] = handleFile('facility-icons', $data['icon']);
        }

        $facility->update($data);
    }

    /**
     * @param $facility
     */
    public function handleDelete($facility)
    {
        $facility->delete();
    }
}
