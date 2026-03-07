<?php

namespace App\Filament\TenantAdmin\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                Section::make('معلومات المنتج')
                    ->columns(2) // توزيع الحقول في عمودين
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم المنتج')
                            ->required(),
                        TextInput::make('price')
                            ->label('السعر')
                            ->required()
                            ->numeric()
                            ->prefix('$'),
                        TextInput::make('stock')
                            ->label('المخزون')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Textarea::make('description')
                            ->label('الوصف')
                            ->default(null)
                            ->columnSpanFull(),
                    ]),

                Section::make('معرض الصور')
                    ->description('ارفع صور المنتج هنا. يمكنك سحب الصور لترتيبها.')
                    ->schema([
                        FileUpload::make('gallery_images')
                            ->label('الصور')
                            ->multiple()            // لرفع أكثر من صورة
                            ->image()               // قبول الصور فقط
                            ->reorderable()         // ترتيب الصور بالسحب والإفلات
                            ->directory('products') // المجلد داخل storage/app/public
                            
                            // 1. جلب الصور عند فتح صفحة التعديل
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record) {
                                    $component->state($record->media->pluck('file_path')->toArray());
                                }
                            })

                            // 2. معالجة الحفظ في جدول media المخصص
                            ->saveRelationshipsUsing(function ($record, $state) {
                                // حذف الميديا القديمة لإعادة بناء المعرض بالترتيب الجديد
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
