<?php

namespace App\Filament\TenantAdmin\Resources\Categories\Schemas;

use App\Services\TenantLocaleService;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
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
                        ->required($locale === app()->getLocale())
                        ->afterStateHydrated(function ($component, $record) use ($locale) {
                            if ($record) {
                                $component->state($record->translate($locale, false)?->name ?? '');
                            }
                        }),
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
                    ->options(fn() => \App\Models\Category::whereNull('parent_id')->get()->pluck('name', 'id'))
                    ->visible(fn(Get $get) => $get('type') === 'sub')
                    ->required(fn(Get $get) => $get('type') === 'sub'),
            ]);
    }
}