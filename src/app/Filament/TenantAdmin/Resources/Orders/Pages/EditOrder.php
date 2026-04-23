<?php

namespace App\Filament\TenantAdmin\Resources\Orders\Pages;

use App\Enums\OrderStatus;
use App\Filament\TenantAdmin\Resources\Orders\OrderResource;
use App\Services\Money\MoneyService;
use App\Services\Orders\OrderService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    public function isReadOnly(): bool
    {
        return $this->getRecord()->status !== OrderStatus::DRAFT;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->hidden(fn () => $this->isReadOnly()),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->hidden(fn () => $this->isReadOnly());
    }

    protected function mutateFormDataBeforeFill(array $data) : array
    {
        $record = $this->getRecord();
        $moneyService = app(MoneyService::class);

        $data['order_type'] = blank($data['customer_id']) ? 'guest' : 'customer';

        if($data['order_type'] === 'customer') $data['use_custom_address'] = true;

        $data['items'] = $record->items->map(function ($item) use ($moneyService) {
            return [
                'id'                   => $item->id,
                'product_id'           => '__name__:' . (
                    is_array($item['product_name'])
                        ? json_encode($item['product_name'])
                        : $item['product_name']
                ),
                'quantity'             => $item->quantity,
                'unit_price'           => $moneyService->fromMinor($item->unit_price),
                'overwrite_price'      => $item->price_overwritten,
            ];
        })->toArray();

        $data['shipping_fees'] = $moneyService->fromMinor($record->shipping_fees ?? 0);
        $data['discount'] = $moneyService->fromMinor($record->discount ?? 0);

        $data['subtotal'] = $moneyService->fromMinor($record->subtotal ?? 0);
        $data['shipping_total'] = $moneyService->fromMinor(($record->subtotal ?? 0) + ($record->shipping_fees ?? 0));
        $data['total'] = $moneyService->fromMinor($record->total ?? 0);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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
                if (empty($item['overwrite_price'])) {
                    $item['overwrite_price_value'] = null;
                } else {
                    $item['overwrite_price_value'] = $moneyService->toMinor((float) ($item['unit_price'] ?? 0));
                }
                unset($item['overwrite_price'], $item['unit_price']);
                return $item;
            }, $data['items']);
        }
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(OrderService::class)->update($record, $data);
    }
}