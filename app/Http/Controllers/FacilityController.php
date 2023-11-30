<?php

namespace App\Http\Controllers;


use App\Exceptions\CustomException;
use App\Http\Services\FacilityService;
use App\Models\Facility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class FacilityController extends Controller
{
    protected FacilityService $service;

    public function __construct(FacilityService $service)
    {
        $this->service = $service;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return successResponse($this->service->getAll());
    }

    /**
     * @param Facility $facility
     * @return JsonResponse
     */
    public function show(Facility $facility): JsonResponse
    {
        return successResponse($this->service->getOne($facility));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'icon' => ['required', File::image()],
            'title' => 'required'
        ]);
        $this->service->handleStore($data);
        return successResponse();
    }

    /**
     * @param Request $request
     * @param Facility $facility
     * @return JsonResponse
     * @throws CustomException
     */
    public function update(Request $request, Facility $facility): JsonResponse
    {
        $data = $request->validate([
            'icon' => ['nullable', File::image()],
            'title' => 'nullable'
        ]);
        $this->service->handleUpdate($facility, $data);
        return successResponse();
    }

    /**
     * @param Facility $facility
     * @return JsonResponse
     */
    public function destroy(Facility $facility): JsonResponse
    {
        $this->service->handleDelete($facility);
        return successResponse();
    }
}
