<?php

namespace App\Filament\TenantAdmin\Resources\TenantUsers\Pages;

use App\Enums\TenantPermission;
use App\Filament\TenantAdmin\Resources\TenantUsers\TenantUserResource;
use App\Models\TenantUserPermission;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTenantUser extends CreateRecord
{
    protected static string $resource = TenantUserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $permValues = $this->extractPermissions($data);

        $record = static::getModel()::create([
            'user_id' => $data['user_id'],
            'role' => $data['role'],
        ]);

        $this->syncPermissionOverrides($record, $permValues);

        return $record;
    }

    private function extractPermissions(array &$data): array
    {
        $out = [];

        foreach (TenantPermission::cases() as $perm) {
            $formKey = "perm_{$perm->value}";

            if (array_key_exists($formKey, $data)) {
                $out[$perm->value] = $data[$formKey];
                unset($data[$formKey]);
            }
        }

        return $out;
    }

    /**
     * write a TenantUserPermission to DB when the not null
     * and differs from the role's default
     */
    private function syncPermissionOverrides(Model $record, array $permValues): void
    {
        $role = $record->role;

        foreach (TenantPermission::cases() as $perm) {
            if ($perm->isLockedFor($role)) {
                continue;
            }

            $submitted = $permValues[$perm->value] ?? null;

            if ($submitted === null) {
                continue;
            }

            $submitted = (bool) $submitted;
            $default   = $perm->defaultFor($role);

            if ($submitted === $default) {
                continue;
            }

            TenantUserPermission::create([
                'tenant_user_id' => $record->id,
                'permission' => $perm->value,
                'granted' => $submitted,
            ]);
        }
    }
}