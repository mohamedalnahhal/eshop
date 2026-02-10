<?php

namespace App\Filament\SuperAdmin\Resources\Tenants\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Hidden;
use App\Enums\TenantStatus;
use App\Enums\Gender;
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
                Section::make('Tenant Information')
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
                
                ]),
                Section::make('Tenant Owner')
                ->schema([
                    TextInput::make('owner_email')
                        ->label('Owner Email')
                        ->email()
                        ->required()
                        ->live(onBlur: true) 
                        ->afterStateUpdated(function (Set $set, ?string $state) {
                            $user = User::where('email', $state)->first();
                            
                            if ($user) {
                                $set('user_exists', true);
                            } else {
                                $set('user_exists', false);
                            }
                        }),

                    Hidden::make('user_exists')
                        ->default(true),

                    TextInput::make('owner_name')
                        ->label('Owner Name')
                        ->required(fn (Get $get) => ! $get('user_exists'))
                        ->visible(fn (Get $get) => ! $get('user_exists')),

                    TextInput::make('owner_password')
                        ->label('Password')
                        ->password()
                        ->required(fn (Get $get) => ! $get('user_exists'))
                        ->visible(fn (Get $get) => ! $get('user_exists')),

                    TextInput::make('username')
                        ->required(fn (Get $get) => ! $get('user_exists'))
                        ->visible(fn (Get $get) => ! $get('user_exists')),
                    
                    TextInput::make('phone')
                        ->tel()
                        ->required(fn (Get $get) => ! $get('user_exists'))
                        ->visible(fn (Get $get) => ! $get('user_exists')),

                    Select::make('gender')
                        ->options(Gender::class)
                        ->required(fn (Get $get) => ! $get('user_exists'))
                        ->visible(fn (Get $get) => ! $get('user_exists')),
                ]),
        ]);
    }
}
