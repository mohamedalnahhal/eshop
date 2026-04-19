<?php

namespace App\Filament\TenantAdmin\Resources\TenantUsers\Pages;

use App\Enums\TenantPermission;
use App\Enums\TenantUserRole;
use App\Filament\TenantAdmin\Resources\TenantUsers\TenantUserResource;
use App\Models\TenantUserPermission;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTenantUser extends EditRecord
{
    protected static string $resource = TenantUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->hidden(fn () => $this->resolvedRole() === TenantUserRole::OWNER),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $permValues = $this->extractPermissions($data);

        $record->update(['role' => $data['role']]);

        $this->syncPermissionOverrides($record, $permValues);

        return $record;
    }

    private function resolvedRole(): TenantUserRole
    {
        $raw = $this->record->role;

        return $raw instanceof TenantUserRole ? $raw : TenantUserRole::from($raw);
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

    private function syncPermissionOverrides(Model $record, array $permValues): void
    {
        $role = $record->role;

        foreach (TenantPermission::cases() as $perm) {
            // clean up locked perm overrides if somehow exists
            if ($perm->isLockedFor($role)) {
                TenantUserPermission::where('tenant_user_id', $record->id)
                    ->where('permission', $perm->value)
                    ->forceDelete();
                continue;
            }

            $submitted = $permValues[$perm->value] ?? null;

            if ($submitted === null) {
                continue;
            }

            $submitted = (bool) $submitted;
            $default   = $perm->defaultFor($role);

            if ($submitted === $default) {
                // remove any existing override when the submitted value matches the role's default
                TenantUserPermission::where('tenant_user_id', $record->id)
                    ->where('permission', $perm->value)
                    ->forceDelete();
            } else {
                TenantUserPermission::withTrashed()->updateOrCreate(
                    [
                        'tenant_user_id' => $record->id,
                        'permission' => $perm->value,
                    ],
                    [
                        'granted' => $submitted,
                        'deleted_at' => null,
                    ]
                );
            }
        }
    }
}