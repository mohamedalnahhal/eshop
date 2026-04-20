<?php

namespace App\Services\Shipping;

use App\Enums\ShippingRateType;
use App\Models\Cart;
use App\Models\ShippingRate;
use App\Models\ShippingZone;
use Illuminate\Support\Collection;

/**
 * resolves which shipping methods are available for a given cart + destination,
 * and calculates the final fee for each available method
 */
class ShippingCalculatorService
{
    /**
     * @param Cart $cart loaded with items.product
     * @param string $countryCode  ISO-3166-1 alpha-2 destination country
     * @return Collection<int, AvailableShippingMethod>
     */
    public function getAvailableMethods(Cart $cart, string $countryCode): Collection
    {
        $cartSubtotal = $cart->total();
        $cartWeightGrams = $this->resolveWeight($cart);

        $zones = $this->resolveZones($countryCode);

        if ($zones->isEmpty()) {
            return collect();
        }

        $available = collect();

        foreach ($zones as $zone) {
            foreach ($zone->methods as $method) {
                if (! $method->is_active) {
                    continue;
                }

                $fee = $this->resolveMethodFee($method->rates, $cartSubtotal, $cartWeightGrams);

                if ($fee === null) {
                    continue;
                }

                $available->push(AvailableShippingMethod::fromModel($method, $fee));
            }
        }

        return $available->sortBy('sortOrder')->values();
    }

    private function resolveZones(string $countryCode): Collection
    {
        $upper = strtoupper($countryCode);

        /** @var Collection<int, ShippingZone> $allActiveZones */
        $allActiveZones = ShippingZone::with(['methods.rates'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        $specific = $allActiveZones->filter(
            fn(ShippingZone $z) => ! $z->isCatchAll() && $z->coversCountry($upper)
        );

        if ($specific->isNotEmpty()) {
            return $specific;
        }

        return $allActiveZones->filter(fn(ShippingZone $z) => $z->isCatchAll());
    }

    /**
     * resolve the applicable fee (in minor units)
     * Returns null when no rate condition matches the cart
     */
    private function resolveMethodFee(
        Collection $rates,
        int        $subtotal,
        int        $weightGrams,
    ): ?int {
        /** @var ShippingRate|null $matchedRate */
        $matchedRate = null;

        foreach ($rates as $rate) {
            if ($this->rateMatches($rate, $subtotal, $weightGrams)) {
                $matchedRate = $rate;
                break;
            }
        }

        if ($matchedRate === null) {
            return null;
        }

        if ($matchedRate->free_above !== null && $subtotal >= $matchedRate->free_above) {
            return 0;
        }

        return match ($matchedRate->rate_type) {
            ShippingRateType::FREE        => 0,
            ShippingRateType::FLAT_RATE,
            ShippingRateType::PRICE_BASED,
            ShippingRateType::WEIGHT_BASED => $matchedRate->fee,
        };
    }

    private function rateMatches(ShippingRate $rate, int $subtotal, int $weightGrams): bool
    {
        return match ($rate->rate_type) {

            ShippingRateType::FLAT_RATE,
            ShippingRateType::FREE =>
                true,

            ShippingRateType::PRICE_BASED =>
                ($rate->condition_min === null || $subtotal >= $rate->condition_min)
                && ($rate->condition_max === null || $subtotal <= $rate->condition_max),

            ShippingRateType::WEIGHT_BASED =>
                ($rate->condition_min === null || $weightGrams >= $rate->condition_min)
                && ($rate->condition_max === null || $weightGrams <= $rate->condition_max),
        };
    }

    private function resolveWeight(Cart $cart): int
    {
        return (int) $cart->items->sum(function ($item) {
            $weight = $item->product?->weight_grams ?? 0;
            return $weight * $item->quantity;
        });
    }
}