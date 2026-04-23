<?php

namespace App\Services\Subscription;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use Carbon\Carbon;

class SubscriptionService
{
    public function getActive(Tenant $tenant): ?TenantSubscription
    {
        return $tenant->subscriptions()
            ->active()
            ->with('subscription')
            ->latest('starts_at')
            ->first();
    }

    public function assignPlan(Tenant $tenant, Subscription $plan, bool $trial = false): TenantSubscription
    {
        $startsAt = Carbon::now();
        $endsAt = $startsAt->copy()->addDays($plan->duration_days);

        return $tenant->subscriptions()->create([
            'subscription_id' => $plan->id,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => $trial ? SubscriptionStatus::TRIALING : SubscriptionStatus::PENDING,
        ]);
    }

    public function activate(TenantSubscription $tenantSubscription): void
    {
        $tenantSubscription->update(['status' => SubscriptionStatus::ACTIVE]);
    }

    public function cancel(TenantSubscription $tenantSubscription): void
    {
        $tenantSubscription->update(['status' => SubscriptionStatus::CANCELLED]);
    }

    public function expire(TenantSubscription $tenantSubscription): void
    {
        $tenantSubscription->update(['status' => SubscriptionStatus::EXPIRED]);
    }

    public function renew(TenantSubscription $current): TenantSubscription
    {
        $plan = $current->subscription;

        $startsAt = $current->ends_at->isFuture() ? $current->ends_at : Carbon::now();
        $endsAt = $startsAt->copy()->addDays($plan->duration_days);

        return $current->tenant->subscriptions()->create([
            'subscription_id' => $plan->id,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => SubscriptionStatus::PENDING,
        ]);
    }

    public function hasReachedProductLimit(Tenant $tenant): bool
    {
        $active = $this->getActive($tenant);

        if (!$active) {
            return true;
        }

        $limit = $active->subscription->max_products;

        if ($limit === 0) {
            return false;
        }

        $count = $tenant->products()->count();

        return $count >= $limit;
    }

    public function expireOverdue(): int
    {
        return TenantSubscription::query()
            ->withoutTenancy()
            ->whereIn('status', [SubscriptionStatus::ACTIVE->value, SubscriptionStatus::TRIALING->value])
            ->where('ends_at', '<', now())
            ->update(['status' => SubscriptionStatus::EXPIRED->value]);
    }
}
