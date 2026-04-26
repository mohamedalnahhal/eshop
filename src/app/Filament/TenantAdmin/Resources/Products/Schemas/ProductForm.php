<?php

namespace App\Filament\TenantAdmin\Resources\Products\Schemas;

use App\Services\TenantLocaleService;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Storage;
use App\Helpers\LocaleHelper;
use App\Services\Money\MoneyService;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        $locales = app(TenantLocaleService::class)->getSupportedLocales();

        $translationTabs = array_map(function ($locale) {
            return Tab::make(strtoupper($locale))
                ->schema([
                    TextInput::make("translations.{$locale}.name")
                        ->label('Product name')
                        ->required($locale === app()->getLocale())
                        ->afterStateHydrated(function ($component, $record) use ($locale) {
                            if ($record) {
                                $component->state($record->translate($locale, false)?->name ?? '');
                            }
                        }),

                    Textarea::make("translations.{$locale}.description")
                        ->label('Description')
                        ->extraAttributes(LocaleHelper::isRtl($locale) ? ['dir' => 'rtl'] : [])
                        ->afterStateHydrated(function ($component, $record) use ($locale) {
                            if ($record) {
                                $component->state($record->translate($locale, false)?->description ?? '');
                            }
                        })
                        ->columnSpanFull(),
                ]);
        }, $locales);

        return $schema
            ->components([
                Section::make('Product information')
                    ->columns(2)
                    ->schema([
                        Tabs::make('Translations')
                            ->tabs($translationTabs)
                            ->scrollable(false) // overflowing tabs collapse into a dropdown
                            ->columnSpanFull(),

                        Select::make('categories')
                            ->label('Categories')
                            ->relationship('categories', 'name', fn ($query) => $query->orderByTranslation('name'))
                            ->multiple()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn (\App\Models\Category $record) => $record->name ?? $record->translate('en')?->name ?? "#{$record->id}")
                            ->getSearchResultsUsing(fn (string $search) => \App\Models\Category::whereTranslationLike('name', "%{$search}%")->limit(50)->get()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('price')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->prefix(MoneyService::getSymbol(tenant()->settings?->currency ?? config('app.default_currency', 'USD')))
                            ->formatStateUsing(fn ($state) => blank($state) ? null : app(MoneyService::class)->fromMinor((int) $state))
                            ->dehydrateStateUsing(fn ($state) => blank($state) ? 0 : app(MoneyService::class)->toMinor((float) $state)),

                        TextInput::make('stock')
                            ->label('Stock')
                            ->required()
                            ->numeric()
                            ->default(0),

                        TextInput::make('weight_grams')
                            ->label('Weight (grams)')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('g')
                            ->placeholder('Optional'),
                    ]),

                Section::make('Photo Gallery')
                    ->description('Upload product images here.')
                    ->schema([
                        FileUpload::make('gallery_images')
                            ->label('Pictures')
                            ->multiple()
                            ->image()
                            ->reorderable()
                            ->directory('products')
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record) {
                                    $component->state($record->media->pluck('file_path')->toArray());
                                }
                            })
                            ->saveRelationshipsUsing(function ($record, $state) {
                                $record->media()->delete();
                                foreach ($state as $path) {
                                    $record->media()->create([
                                        'collection_name' => 'products',
                                        'file_path'       => $path,
                                        'file_type'       => 'image',
                                        'file_size'       => Storage::disk('public')->exists($path)
                                                             ? Storage::disk('public')->size($path)
                                                             : 0,
                                    ]);
                                }
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}