<?php

namespace App\Filament\TenantAdmin\Resources\PaymentLogs\Pages;

use App\Filament\TenantAdmin\Resources\PaymentLogs\PaymentLogResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use App\Filament\TenantAdmin\Resources\PaymentLogs\Tables\PaymentLogsTable;

class ListPaymentLogs extends ListRecords
{
    protected static string $resource = PaymentLogResource::class;

    protected function getHeaderActions(): array
    {
        return []; 
    }

    public function table(Table $table): Table
    {
        return PaymentLogsTable::configure($table);
    }
}