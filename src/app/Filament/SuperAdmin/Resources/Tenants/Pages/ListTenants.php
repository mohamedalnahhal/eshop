<?php

namespace App\Filament\SuperAdmin\Resources\Tenants\Pages;

use App\Filament\SuperAdmin\Resources\Tenants\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\ActionGroup;

class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Tenant'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ActionGroup::make([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
                ->label('Actions')
                ->icon('heroicon-m-ellipsis-vertical')
                ->button(),
        ];
    }
}