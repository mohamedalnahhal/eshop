<?php

namespace App\Filament\SuperAdmin\Resources\Tenants\Pages;

use App\Filament\SuperAdmin\Resources\Tenants\TenantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use \Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $tenant = $this->getRecord();

        $domain = $tenant->domain?->domain ?? '';
        $centralDomain = '.' . config('tenancy.central_domains')[0];
        $data['subdomain'] = str_replace($centralDomain, '', $domain);

        $data['owner_email'] = $tenant->owner->first()?->email;

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            $tenantData = collect($data)->except(['subdomain', 'owner_email'])->toArray();
            $record->update($tenantData);

            $record->domain()->updateOrCreate(
                ['tenant_id' => $record->id],
                ['domain' => $data['subdomain'] . '.' . config('tenancy.central_domains')[0]]
            );

            return $record;
        });
    }
}
