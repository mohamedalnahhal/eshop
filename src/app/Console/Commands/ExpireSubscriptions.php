<?php

namespace App\Console\Commands;

use App\Services\Subscription\SubscriptionService;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';

    protected $description = 'Mark all active subscriptions whose end date has passed as expired';

    public function handle(SubscriptionService $service): int
    {
        $count = $service->expireOverdue();

        $this->info("Expired {$count} subscription(s).");

        return self::SUCCESS;
    }
}
