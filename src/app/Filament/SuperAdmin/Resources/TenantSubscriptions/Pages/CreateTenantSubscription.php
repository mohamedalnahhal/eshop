<?php

namespace App\Filament\SuperAdmin\Resources\TenantSubscriptions\Pages;

use App\Enums\SubscriptionStatus;
use App\Filament\SuperAdmin\Resources\TenantSubscriptions\TenantSubscriptionResource;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateTenantSubscription extends CreateRecord
{
    protected static string $resource = TenantSubscriptionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $trial = $data['trial'] ?? false;
        unset($data['trial']);

        $plan = Subscription::find($data['subscription_id']);
        $startsAt = Carbon::parse($data['starts_at'] ?? now());
        $endsAt = $data['ends_at'] ?? $startsAt->copy()->addDays($plan->duration_days);

        $data['starts_at'] = $startsAt;
        $data['ends_at'] = $endsAt;
        $data['status'] = $trial ? SubscriptionStatus::TRIALING : ($data['status'] ?? SubscriptionStatus::PENDING);

        return $data;
    }
}
