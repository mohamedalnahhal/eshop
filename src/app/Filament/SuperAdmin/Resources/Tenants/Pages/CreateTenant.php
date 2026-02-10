<?php

namespace App\Filament\SuperAdmin\Resources\Tenants\Pages;

use App\Enums\UserRole;
use App\Filament\SuperAdmin\Resources\Tenants\TenantResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Support\Str;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $user = User::where('email', $data['owner_email'])->first();

            if (! $user) {
                $user = User::create([
                    'name'     => $data['owner_name'],
                    'email'    => $data['owner_email'],
                    'password' => Hash::make($data['owner_password']),
                    'username' => $data['username'],
                    'phone'    => $data['phone'],
                    'gender'   => $data['gender'],
                    'role'     => UserRole::TENANT_OWNER,
                ]);
            }

            $tenantData = collect($data)
                ->except(['owner_name', 'owner_email', 'owner_password', 'username', 'phone', 'gender', 'user_exists'])
                ->toArray();

            $tenant = static::getModel()::create($tenantData);

            $tenant->users()->attach($user->id, [
                'id' => Str::uuid(),
                'role' => 0,
            ]);

            return $tenant;
        });
    }
}
