<?php

namespace App\Filament\TenantAdmin\Pages;

use App\Enums\SubscriptionStatus;
use App\Models\TenantSubscription;
use App\Services\Subscription\SubscriptionService;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class MySubscription extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'My Subscription';
    protected static ?int $navigationSort = 98;
    protected static string|\UnitEnum|null $navigationGroup = 'Shop Settings';
    protected static ?string $title = 'My Subscription';
    protected string $view = 'filament.tenant-admin.pages.my-subscription';

    public ?TenantSubscription $activeSubscription = null;
    public int $productCount = 0;
    public int $productLimit = 0;

    public function mount(): void
    {
        $tenant = tenant();

        $this->activeSubscription = app(SubscriptionService::class)->getActive($tenant);

        $this->productCount = $tenant->products()->count();

        $this->productLimit = $this->activeSubscription?->subscription->max_products ?? 0;
    }

    public function getProductUsagePercentage(): int
    {
        if ($this->productLimit === 0) return 0;
        return (int) min(100, round(($this->productCount / $this->productLimit) * 100));
    }
}
