<?php

namespace App\Filament\SuperAdmin\Resources\Tenants\Pages;

use App\Enums\UserRole;
use App\Filament\SuperAdmin\Resources\Tenants\TenantResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $user = User::where('email', $data['owner_email'])->first();

            $tenantData = collect($data)
                ->except(['subdomain'])
                ->toArray();

            $tenant = static::getModel()::create($tenantData);
            $tenant->domain()->create(['domain' => $data['subdomain'] . '.' . config('tenancy.central_domains')[0]]);

            $tenant->users()->attach($user->id, [
                'id' => Str::uuid(),
                'role' => UserRole::TENANT_OWNER,
            ]);

            return $tenant;
        });
    }
}
