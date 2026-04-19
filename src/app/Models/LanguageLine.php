<?php

namespace App\Models;

use Spatie\TranslationLoader\LanguageLine as BaseLanguageLine;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LanguageLine extends BaseLanguageLine
{
    use HasUuids;

    public static function getTranslationsForGroup(string $locale, string $group): array
    {
        return static::query()
            ->where('group', $group)
            ->get()
            ->reduce(function ($lines, self $languageLine) use ($locale) {
                $translation = $languageLine->getTranslation($locale);
                if ($translation !== null) {
                    Arr::set($lines, $languageLine->key, $translation);
                }
                return $lines;
            }, []);
    }
}