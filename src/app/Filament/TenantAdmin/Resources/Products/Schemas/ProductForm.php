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
                        ->required($locale === app()->getLocale()),

                    Textarea::make("translations.{$locale}.description")
                        ->label('Description')
                        ->extraAttributes(LocaleHelper::isRtl($locale) ? ['dir' => 'rtl'] : [])
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
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('price')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->prefix('$'),

                        TextInput::make('stock')
                            ->label('Stock')
                            ->required()
                            ->numeric()
                            ->default(0),
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