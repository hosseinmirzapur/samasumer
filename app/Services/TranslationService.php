<?php


namespace App\Services;


class TranslationService
{
    public static function supportedLanguages()
    {
        return config('app.supported_locales');
    }
}
