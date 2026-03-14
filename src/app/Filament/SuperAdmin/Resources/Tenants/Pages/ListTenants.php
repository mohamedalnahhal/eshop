<?php

namespace App\Filament\SuperAdmin\Resources\Tenants\Pages;

use App\Filament\SuperAdmin\Resources\Tenants\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\ActionGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Enums\UserRole;
use App\Models\User;

class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Tenant')
                ->using(function (array $data, string $model): Model {
                return DB::transaction(function () use ($data, $model) {
                    $user = User::where('email', $data['owner_email'])->first();

                    $tenantData = collect($data)
                        ->except(['subdomain'])
                        ->toArray();

                    $tenant = $model::create($tenantData);
                    
                    $tenant->domain()->create([
                        'domain' => $data['subdomain'] . '.' . config('tenancy.central_domains')[0]
                    ]);

                    $tenant->users()->attach($user->id, [
                        'id' => Str::uuid(),
                        'role' => UserRole::TENANT_OWNER,
                    ]);

                    return $tenant;
                });
                }),
        ];
    }
}