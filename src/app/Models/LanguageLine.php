<?php

namespace App\Models;

use Spatie\TranslationLoader\LanguageLine as BaseLanguageLine;

class LanguageLine extends BaseLanguageLine
{
    public static function getTranslationsForGroup(string $locale, string $group): array
    {
        return static::query()
            ->where('group', $group)
            ->get()
            ->reduce(function ($lines, self $languageLine) use ($locale) {
                $translation = $languageLine->getTranslation($locale);
                if ($translation !== null) {
                    array_set($lines, $languageLine->key, $translation);
                }
                return $lines;
            }, []);
    }
}