<?php

namespace App\Filament\SuperAdmin\Resources\Tenants\Schemas;

use Filament\Schemas\Components\Utilities\Get;
use App\Enums\TenantStatus;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Stancl\Tenancy\Database\Models\Domain;
use Illuminate\Database\Eloquent\Model;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('subdomain')
                    ->prefix('https://')
                    ->suffix('.' . config('tenancy.central_domains')[0])
                    ->required()
                    ->regex('/^[a-z0-9-]+$/')
                    ->rules([
                        function (Get $get, ?Model $record) {
                            return function (string $attribute, $value, \Closure $fail) use ($record)  {
                                $centralDomain = config('tenancy.central_domains')[0];
                                $fullDomain = $value . '.' . $centralDomain;

                                $query = Domain::where('domain', $fullDomain);

                                if ($record) {
                                    $query->where('tenant_id', '!=', $record->id);
                                }

                                if ($query->exists()) {
                                    $fail("The subdomain '{$value}' is already taken.");
                                }
                            };
                        },
                    ]),
                Select::make('status')
                    ->options(TenantStatus::class)
                    ->default('active'),
                TextInput::make('owner_email')
                    ->label('Owner Email')
                    ->email()
                    ->required()
                    ->rules([
                        function () {
                            return function (string $attribute, $value, \Closure $fail) {
                                $user = User::where('email', $value)->first();
                        
                                if (!$user) {
                                    $fail("No user with '{$value}' email exists.");
                                }
                            };
                        },
                    ]),
        ]);
    }
}
