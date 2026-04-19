<?php

namespace App\Helpers;

class LocaleHelper
{
    public static function isRtl(string $locale): bool
    {
        return in_array($locale, ['ar', 'he', 'fa', 'ur', 'ps', 'ug']);
    }
}