<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\PaymentMethodResource\Pages;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Schemas\Components\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentMethodResource extends Resource
{
    // 1. ربط المودل بالمورد
    protected static ?string $model = PaymentMethod::class;

    // أيقونة القائمة الجانبية
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    // اسم القائمة الجانبية (Payment Methods)
    protected static ?string $navigationLabel = 'Payment Methods';

    // المجموعة في القائمة (اختياري، يمكنك إزالته إذا لم يكن مطلوباً)
    protected static ?string $navigationGroup = 'Settings';

    public static function form(Schema $schema): Schema
    {
        return SubscriptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            // المتطلب: صفحة لرؤية الـ Payment Methods
            ->columns([
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('provider')
                    ->label('Provider')
                    ->searchable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                // عرض أيقونة تدل على وجود ملف أم لا
                Tables\Columns\IconColumn::make('config')
                    ->label('Config Uploaded')
                    ->boolean()
                    ->state(fn ($record) => !empty($record->config)),
            ])
            ->actions([
                // المتطلب: امكانية تعديل Payment Methods
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        // المتطلب: وجود صفحة "New Method" يتم تلقائياً عبر صفحة Create
        return [
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}