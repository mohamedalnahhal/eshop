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
        return $this->getAvailableMethodsForValues(
            countryCode: $countryCode,
            subtotal: $cart->total(),
            weightGrams: $cart->weight(),
        );
    }

    /**
     * resolve available shipping methods from raw values
     * used for express-checkout
     * 
     * @param string $countryCode  ISO-3166-1 alpha-2 destination country
     * @return Collection<int, AvailableShippingMethod>
     */
    public function getAvailableMethodsForValues(
        string $countryCode,
        int $subtotal,
        int $weightGrams,
    ): Collection {

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

                $fee = $this->resolveMethodFee($method->rates, $subtotal, $weightGrams);

                if ($fee === null) {
                    continue;
                }

                $available->push(AvailableShippingMethod::fromModel($method, $fee));
            }
        }

        return $available->sortBy('sortOrder')->values();
    }

    /**
     * resolve the applicable fee (in minor units)
     * Returns null when no rate condition matches the cart
     */
    public function resolveMethodFee(
        Collection $rates,
        int $subtotal,
        int $weightGrams,
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

    private function resolveZones(string $countryCode): Collection
    {
        $upper = strtoupper($countryCode);

        /** @var Collection<int, ShippingZone> $allActiveZones */
        $allActiveZones = ShippingZone::with(['methods.rates'=> function ($query) {
            $query->orderBy('sort_order', 'asc');
        }])->where('is_active', true)
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

    private function rateMatches(ShippingRate $rate, int $subtotal, int $weightGrams): bool
    {
        $minOk = $rate->condition_min === null || match ($rate->rate_type) {
            ShippingRateType::WEIGHT_BASED => $weightGrams >= $rate->condition_min,
            default => $subtotal >= $rate->condition_min,
        };
    
        $maxOk = $rate->condition_max === null || match ($rate->rate_type) {
            ShippingRateType::WEIGHT_BASED => $weightGrams <= $rate->condition_max,
            default => $subtotal <= $rate->condition_max,
        };
    
        return $minOk && $maxOk;
    }
}