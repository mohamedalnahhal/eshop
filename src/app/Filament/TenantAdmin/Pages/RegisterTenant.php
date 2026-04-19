<?php

namespace App\Filament\TenantAdmin\Pages;

use App\Models\Tenant;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant as BaseRegisterTenant;

class RegisterTenant extends BaseRegisterTenant
{
    protected static ?string $model = Tenant::class;

    public static function getLabel(): string
    {
        return 'تسجيل متجر جديد';
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('اسم المتجر')
                ->required()
                ->maxLength(255),

            TextInput::make('subdomain')
                ->label('الرابط المميز (Subdomain)')
                ->unique('tenants', 'subdomain')
                ->required()
                ->maxLength(255)
                ->regex('/^[a-zA-Z0-9\-]+$/')
                ->helperText('استخدم أحرف إنجليزية وأرقام وشرطات فقط'),
        ];
    }

    protected function handleRegistration(array $data): Tenant
    {
        $tenant = Tenant::create($data);

        $tenant->users()->attach(auth()->user(), ['role' => 'owner']);

        return $tenant;
    }
}