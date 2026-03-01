<?php

namespace App\Filament\TenantAdmin\Resources\Orders\Pages;

use App\Filament\TenantAdmin\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['discount'] = max(0, min(100, (float) ($data['discount'] ?? 0)));

        // temporarily resetting totals to 0 to completely ignore any tampered frontend math
        $data['total_price'] = 0;
        $data['final_price'] = 0;

        return $data;
    }

    protected function afterSave(): void
    {
        $order = $this->record;
        $order->load('items.product');

        $realTotalPrice = 0;
        foreach ($order->items as $item) {
            $productPrice = (float) ($item->product->price ?? 0);
            $realTotalPrice += ($item->quantity * $productPrice);
        }

        $discountAmount = $realTotalPrice * ($order->discount / 100);

        // update the final calculated totals
        $order->updateQuietly([
            'total_price' => $realTotalPrice,
            'final_price' => max(0, $realTotalPrice - $discountAmount),
        ]);
    }
}
