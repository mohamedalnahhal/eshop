<?php

namespace App\Filament\TenantAdmin\Resources\PaymentLogs;

use App\Filament\TenantAdmin\Resources\PaymentLogs\Pages;
use App\Models\Payment;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;


class PaymentLogResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static string|\UnitEnum|null $navigationGroup = 'Financials';
    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Payment Log';
    protected static ?string $pluralModelLabel = 'Payment Logs';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
    return parent::getEloquentQuery()->with('payable');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentLogs::route('/'),
            'view' => Pages\ViewPaymentLog::route('/{record}'),
        ];
    }
    
}