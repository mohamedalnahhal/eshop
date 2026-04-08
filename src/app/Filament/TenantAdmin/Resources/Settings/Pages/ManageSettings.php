<?php

namespace App\Filament\TenantAdmin\Resources\Settings\Pages;

use App\Filament\TenantAdmin\Resources\Settings\SettingResource;
use App\Filament\TenantAdmin\Resources\Settings\Schemas\SettingsForm;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;
use App\Models\TenantSetting;
use Filament\Notifications\Notification;

class ManageSettings extends EditRecord
{
    protected static string $resource = SettingResource::class;

    public ?array $data = [];

    public function mount(int | string $record = null): void
    {
        $this->record = TenantSetting::firstOrCreate(
            ['tenant_id' => tenant('id')],
            ['store_name' => tenant('name'), 'language' => 'ar']
        );

        $this->form->fill($this->record->toArray());
    }

  
   public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
{
    $formData = $this->form->getState();

    $this->record->update($formData);

    if (isset($formData['store_name'])) {
        tenant()->update([
            'name' => $formData['store_name']
        ]);
    }

    if ($shouldSendSavedNotification) {
        \Filament\Notifications\Notification::make()
            ->success()
            ->title('Settings and tenant have been updated successfully')
            ->send();
    }
}

    protected function resolveRecord(int | string $key): TenantSetting
    {
        return TenantSetting::first();
    }

    public function form(Schema $schema): Schema
    {
        return SettingsForm::configure($schema);
    }
}