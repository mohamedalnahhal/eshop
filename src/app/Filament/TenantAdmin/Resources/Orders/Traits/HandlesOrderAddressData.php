<?php

namespace App\Filament\TenantAdmin\Resources\Orders\Traits;

use App\Models\Address;

trait HandlesOrderAddressData
{
    protected function resolveAddressData(array $data): array
    {
        $isGuest = blank($data['customer_id'] ?? null);

        if (!$isGuest) {
            $usesCustomAddress = (bool) ($data['use_custom_address'] ?? false);

            if ($usesCustomAddress) {
                $data['shipping_address_id'] = null;
            } else {
                $addressId = $data['shipping_address_id'] ?? null;
                if ($addressId && $address = Address::find($addressId)) {
                    $data['shipping_address'] = $address->only([
                        'name', 'line_1', 'line_2', 'city', 'state', 'postal_code', 'country', 'lng', 'lat'
                    ]);
                }
            }
            $data['guest_name']  = null;
            $data['guest_email'] = null;
            $data['guest_phone'] = null;
        } else {
            $data['customer_id'] = null;
        }

        return $data;
    }
}