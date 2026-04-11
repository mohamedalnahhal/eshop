<?php

namespace App\Filament\TenantAdmin\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product information')
                    ->columns(2) 
                    ->schema([
                        TextInput::make('name')
                            ->label('Product name')
                            ->required(),
                         Select::make('categories')
                            ->label('Categories')
                            ->relationship('categories', 'name') 
                            ->multiple() 
                            ->preload()
                            ->searchable()
                            ->required(),
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
                        Textarea::make('description')
                            ->label('Description')
                            ->default(null)
                            ->columnSpanFull(),
                    ]),

                Section::make('Photo Gallery')
                    ->description('Upload product images here. You can drag and drop images to arrange them.')
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
                                        'file_path' => $path,
                                        'file_type' => 'image',
                                        'file_size' => Storage::disk('public')->exists($path) 
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
