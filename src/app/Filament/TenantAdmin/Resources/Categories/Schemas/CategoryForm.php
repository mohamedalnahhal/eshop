<?php

namespace App\Filament\TenantAdmin\Resources\Categories\Schemas;

use App\Services\TenantLocaleService;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        $locales = app(TenantLocaleService::class)->getSupportedLocales();

        $translationTabs = array_map(function ($locale) {
            return Tab::make(strtoupper($locale))
                ->schema([
                    TextInput::make("translations.{$locale}.name")
                        ->label('Name')
                        ->required($locale === app()->getLocale()),
                ]);
        }, $locales);

        return $schema
            ->components([
                Tabs::make('Translations')
                    ->tabs($translationTabs),

                Select::make('type')
                    ->options([
                        'main' => 'Main',
                        'sub'  => 'Sub-category',
                    ])
                    ->live()
                    ->required(),

                Select::make('parent_id')
                    ->label('Parent Category')
                    ->relationship('parent', 'name', fn($query) => $query->whereNull('parent_id'))
                    ->visible(fn(Get $get) => $get('type') === 'sub')
                    ->required(fn(Get $get) => $get('type') === 'sub'),
            ]);
    }
}