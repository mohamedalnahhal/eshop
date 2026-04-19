<?php

namespace App\Filament\TenantAdmin\Resources\Orders\Pages;

use App\Filament\TenantAdmin\Resources\Orders\OrderResource;
use App\Services\Money\MoneyService;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
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
}
