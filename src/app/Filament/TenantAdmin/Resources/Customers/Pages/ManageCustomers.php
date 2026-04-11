<?php

namespace App\Filament\TenantAdmin\Resources\Customers\Pages;

use App\Filament\TenantAdmin\Resources\Customers\CustomerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions;
use App\Models\User; 
use Illuminate\Support\Str;

class ManageCustomers extends ManageRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
          Actions\CreateAction::make()
                ->after(function (User $record) {
                    $record->tenants()->attach(tenant('id'), [
                        'id' => (string) Str::uuid(), 
                        'role' => 0, 
                    ]);
                }),
        ];
    }
}
