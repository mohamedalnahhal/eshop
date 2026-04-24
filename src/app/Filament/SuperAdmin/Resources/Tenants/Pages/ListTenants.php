<?php

namespace App\Filament\SuperAdmin\Resources\Tenants\Pages;

use App\Enums\TenantUserRole;
use App\Filament\SuperAdmin\Resources\Tenants\TenantResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;

class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Shop')
                ->using(function (array $data, string $model): Model {
                return DB::transaction(function () use ($data, $model) {
                    $owner = User::where('email', $data['owner_email'])->first();

                    $tenantData = collect($data)
                        ->except(['subdomain'])
                        ->toArray();

                    $tenantData['owner_id'] = $owner->id;

                    $tenant = $model::create($tenantData);
                    
                    $tenant->domain()->create([
                        'domain' => $data['subdomain'] . '.' . config('tenancy.central_domains')[0]
                    ]);

                    $tenant->users()->attach($owner->id, [
                        'id' => Str::uuid(),
                        'role' => TenantUserRole::OWNER,
                    ]);

                    return $tenant;
                });
                }),
        ];
    }
}