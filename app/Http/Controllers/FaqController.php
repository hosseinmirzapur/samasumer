<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Services\FaqService;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FaqController extends Controller
{
    protected FaqService $service;

    public function __construct(FaqService $service)
    {
        $this->service = $service;
        $this->middleware(['auth:sanctum', 'admin'])->except(['index', 'show']);
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return successResponse($this->service->getInfo());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'question.*' => 'required',
            'answer.*' => 'required',
            'type' => ['required', Rule::in(Faq::TYPES)]
        ]);
        $this->service->store($data);
        return successResponse();
    }

    /**
     * @param Faq $faq
     * @return JsonResponse
     */
    public function show(Faq $faq): JsonResponse
    {
        return successResponse($this->service->findFaq($faq));
    }

    /**
     * @param Faq $faq
     * @return JsonResponse
     */
    public function destroy(Faq $faq): JsonResponse
    {
        $this->service->delete($faq);
        return successResponse();
    }

    /**
     * @param Request $request
     * @param Faq $faq
     * @return JsonResponse
     * @throws CustomException
     */
    public function update(Request $request, Faq $faq): JsonResponse
    {
        $data = $request->validate([
            'answer' => 'nullable',
            'question' => 'nullable'
        ]);
        $this->service->update($data, $faq);
        return successResponse();
    }

    /**
     * @param Faq $faq
     * @param $locale
     * @return JsonResponse
     * @throws CustomException
     */
    public function removeTrans(Faq $faq, $locale): JsonResponse
    {
        $this->service->removeTrans($faq, $locale);
        return successResponse();
    }

    /**
     * @param Faq $faq
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function addTrans(Faq $faq, Request $request): JsonResponse
    {
        $data = $request->validate([
            'locale' => 'required',
            'question' => 'required',
            'answer' => 'required'
        ]);
        $this->service->addTranslation($faq, $data);
        return successResponse();
    }
}
