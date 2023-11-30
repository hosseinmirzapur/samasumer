<?php


namespace App\Http\Services;

use App\Exceptions\CustomException;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Services\TranslationService;
use JetBrains\PhpStorm\ArrayShape;

class FaqService
{
    /**
     * @return array
     */
    #[ArrayShape(['faqs' => "\Illuminate\Http\Resources\Json\AnonymousResourceCollection"])]
    public function getInfo(): array
    {
        $faqs = Faq::query()->latest()->get();
        return [
            'faqs' => FaqResource::collection($faqs)
        ];
    }

    /**
     * @param array $data
     * @throws CustomException
     */
    public function store(array $data)
    {
        $this->validateLanguages($data);
        Faq::query()->create($data);
    }

    /**
     * @param $data
     * @throws CustomException
     */
    protected function validateLanguages($data)
    {
        foreach ($data['question'] as $language => $text) {
            $this->localeIsValid($language);
        }
        foreach ($data['answer'] as $language => $text) {
            $this->localeIsValid($language);
        }
    }

    /**
     * @param $faq
     * @return array
     */
    #[ArrayShape(['faq' => "\App\Http\Resources\FaqResource"])]
    public function findFaq($faq): array
    {
        return [
            'faq' => FaqResource::make($faq)
        ];
    }

    /**
     * @param $data
     * @param $faq
     * @throws CustomException
     */
    public function update($data, $faq)
    {
        $data = filterData($data);
        $faq->update($data);
    }

    public function delete($faq)
    {
        $faq->delete();
    }

    /**
     * @param Faq $faq
     * @param $locale
     * @throws CustomException
     */
    public function removeTrans(Faq $faq, $locale)
    {
        $this->localeIsValid($locale);
        $faq->forgetTranslation('question', $locale);
        $faq->forgetTranslation('answer', $locale);
    }

    /**
     * @param Faq $faq
     * @param $data
     * @throws CustomException
     */
    public function addTranslation(Faq $faq, $data)
    {
        $this->localeIsValid($data['locale']);
        $faq->setTranslation('question', $data['locale'], $data['question']);
        $faq->setTranslation('answer', $data['locale'], $data['answer']);
    }

    /**
     * @param $locale
     * @throws CustomException
     */
    protected function localeIsValid($locale)
    {
        if (!in_array($locale, TranslationService::supportedLanguages())) {
            throw new CustomException('UNSUPPORTED_LANGUAGE');
        }
    }

}
