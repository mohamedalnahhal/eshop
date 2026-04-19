<?php

namespace App\Filament\TenantAdmin\Resources\TenantUsers\Schemas;

use App\Enums\TenantPermission;
use App\Enums\TenantUserRole;
use App\Models\User;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TenantUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('User / Member')
                ->icon('heroicon-o-user')
                ->columns(2)
                ->schema([
                    TextInput::make('user_id')
                        ->label('User')
                        ->placeholder('Email or username')
                        ->required()
                        ->disabledOn('edit')
                        ->columnSpanFull()
                        ->formatStateUsing(function ($state, $record) {
                            return $record?->user?->username ?? $state;
                        })
                        ->rules([
                            fn () => function (string $attribute, $value, Closure $fail) {
                                $exists = User::where('email', $value)->orWhere('username', $value)->exists();

                                if (! $exists) {
                                    $fail('No user was found with that email or username.');
                                }
                            },
                        ])
                        ->dehydrateStateUsing(function ($state) {
                            $user = User::where('email', $state)->orWhere('username', $state)->first();

                            return $user?->id ?? $state;
                        }),

                    Select::make('role')
                        ->label('Role')
                        ->options(
                            collect(TenantUserRole::cases())
                                ->reject(fn ($r) => $r === TenantUserRole::OWNER)
                                ->mapWithKeys(fn ($r) => [$r->value => $r->getLabel()])
                        )
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($set, $state, $record) {
                            foreach (TenantPermission::cases() as $perm) {
                                $set(
                                    "perm_{$perm->value}",
                                    self::updatePermissionToggleState(
                                        $record,
                                        $perm,
                                        TenantUserRole::tryFrom((int) $state))
                                );
                            }
                        }),

                    TextEntry::make('role_note')
                        ->state('Owner role is reserved for the tenant owner and cannot be assigned here.')
                        ->color('gray')
                        ->columnSpanFull(),
                ]),

            Section::make('Permission Overrides')
                ->icon('heroicon-o-shield-check')
                ->description('Override the default permissions for this role. Locked permissions cannot be changed.')
                ->schema(static::permissionToggles()),

        ]);
    }

    private static function permissionToggles(): array
    {
        $components = [];

        foreach (TenantPermission::cases() as $perm) {
            $components[] = Toggle::make("perm_{$perm->value}")
                ->label($perm->label())
                ->formatStateUsing(function ($record) use ($perm) {
                    return self::updatePermissionToggleState($record, $perm);
                })
                ->disabled(function ($get, $record) use ($perm) {
                    $roleValue = $get('role') ?? $record?->role;
                    if ($roleValue === null) return false;

                    $role = $roleValue instanceof TenantUserRole
                        ? $roleValue
                        : TenantUserRole::tryFrom((int) $roleValue);

                    return $role && $perm->isLockedFor($role);
                })
                ->helperText(function ($set, $get, $record) use ($perm) {
                    $roleValue = $get('role') ?? $record?->role;
                    if ($roleValue === null) return null;

                    $role = $roleValue instanceof TenantUserRole
                        ? $roleValue
                        : TenantUserRole::tryFrom((int) $roleValue);

                    if ($role && $perm->isLockedFor($role)) {
                        return 'Locked for this role.';
                    }
                    $default = $role ? ($perm->defaultFor($role) ? 'Granted' : 'Denied') : null;
                    return $default ? "Role default: {$default}" : null;
                });
        }

        return $components;
    }

    private static function updatePermissionToggleState($record, $perm, ?TenantUserRole $state = null) {
        if (!$record && $state == null) {
            return null;
        }

        $role = $state != null? $state : $record->role;

        if ($perm->isLockedFor($role)) {
            return $perm->defaultFor($role);
        }

        $override = $record? $record->permissions()
            ->where('permission', $perm->value)
            ->whereNull('deleted_at')
            ->first() : null;

        return $override !== null
            ? $override->granted
            : $perm->defaultFor($role);
    }
}