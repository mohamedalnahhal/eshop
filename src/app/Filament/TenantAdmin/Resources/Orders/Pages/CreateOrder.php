<?php

namespace App\Filament\TenantAdmin\Resources\Orders\Pages;

use App\Filament\TenantAdmin\Resources\Orders\OrderResource;
use App\Filament\TenantAdmin\Resources\Orders\Traits\HandlesOrderAddressData;
use App\Services\Orders\OrderService;
use App\Services\Money\MoneyService;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateOrder extends CreateRecord
{
    use HandlesOrderAddressData;

    protected static string $resource = OrderResource::class;
    public bool $draft = true; 

    protected function getFormActions(): array
    {
        return [
            Action::make('createDraft')
                ->label('Save as Draft')
                ->color('gray')
                ->action(function () {
                    $this->draft = true;
                    $this->create();
                }),

            Action::make('createPending')
                ->label('Create Order')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Confirm Direct Order Creation')
                ->modalDescription('Are you sure you want to create this order? Once created as pending, it cannot be modified later.')
                ->modalSubmitActionLabel('Yes, Create Order')
                ->action(function () {
                    $this->draft = false;
                    $this->create();
                }),

            $this->getCancelFormAction(),
        ];
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $moneyService = app(MoneyService::class);
        
        $data['discount'] = $moneyService->toMinor((float) ($data['discount'] ?? 0));
        $data['shipping_fees'] = $moneyService->toMinor((float) ($data['shipping_fees'] ?? 0));
        
        $data = $this->resolveAddressData($data);

        $data = $this->mutateItems($data, $moneyService);

        unset(
            $data['use_custom_address'],
            $data['shipping_address_id'],
            $data['order_type'],
            $data['shipping_total'],
            $data['subtotal'],
            $data['total'],
        );

        return $data;
    }

    protected function mutateItems(array $data, MoneyService $moneyService): array
    {
        if (!empty($data['items'])) {
            $data['items'] = array_map(function ($item) use ($moneyService) {
                if ($item['overwrite_price']) {
                    $item['overwrite_price_value'] = $moneyService->toMinor((float) ($item['unit_price'] ?? 0));
                } else {
                    $item['overwrite_price_value'] = null;
                }
                $item['quantity'] = (int) $item['quantity'];
                unset($item['overwrite_price'], $item['unit_price']);
                return $item;
            }, $data['items']);
        }
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return app(OrderService::class)->create($data, $this->draft);
    }
}