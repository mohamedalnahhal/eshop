<?php

return [
    'translation_loaders' => [
        Spatie\TranslationLoader\TranslationLoaders\Db::class,
    ],

    'model' => App\Models\LanguageLine::class,

    'translation_manager' => Spatie\TranslationLoader\TranslationLoaderManager::class,

    'cache_store' => 'array',
];