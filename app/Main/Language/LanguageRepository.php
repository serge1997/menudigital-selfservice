<?php
namespace App\Main\Language;

class LanguageRepository implements LanguageRepositoryInterface
{
    public function setSysLanguage($request)
    {
        $request->session()->put('lang', $request->lang);
    }
}
