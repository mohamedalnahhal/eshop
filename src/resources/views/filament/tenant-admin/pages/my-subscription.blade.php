<x-filament-panels::page>
    @if($activeSubscription)
        @php
            $plan = $activeSubscription->subscription;
            $status = $activeSubscription->status;
            $daysLeft = $activeSubscription->daysRemaining();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Plan Card --}}
            <div class="md:col-span-2 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $plan->name }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            ${{ number_format($plan->price / 100, 2) }} / {{ $plan->duration_days }} days
                        </p>
                    </div>
                    <x-filament::badge :color="$status->color()">
                        {{ $status->label() }}
                    </x-filament::badge>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-6 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Started</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                            {{ $activeSubscription->starts_at->format('M d, Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Expires</p>
                        <p class="text-sm font-medium {{ $daysLeft <= 7 ? 'text-danger-600' : 'text-gray-900 dark:text-white' }} mt-1">
                            {{ $activeSubscription->ends_at->format('M d, Y') }}
                            <span class="text-xs text-gray-400">({{ $daysLeft }} days left)</span>
                        </p>
                    </div>
                </div>

                @if($plan->features)
                    <div class="mt-6">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Plan Features</p>
                        <ul class="space-y-1">
                            @foreach($plan->features as $feature)
                                <li class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    @icon('heroicon-o-check-circle', 'w-4 h-4 text-success-500 flex-shrink-0')
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Usage Card --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Product Usage</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $productCount }}
                        @if($productLimit > 0)
                            <span class="text-lg font-normal text-gray-400">/ {{ $productLimit }}</span>
                        @else
                            <span class="text-lg font-normal text-gray-400">/ ∞</span>
                        @endif
                    </p>

                    @if($productLimit > 0)
                        @php $pct = $this->getProductUsagePercentage() @endphp
                        <div class="mt-4 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div
                                class="h-2 rounded-full {{ $pct >= 90 ? 'bg-danger-500' : ($pct >= 70 ? 'bg-warning-500' : 'bg-success-500') }}"
                                style="width: {{ $pct }}%"
                            ></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">{{ $pct }}% used</p>
                    @else
                        <p class="text-xs text-gray-400 mt-2">Unlimited products</p>
                    @endif
                </div>

            </div>

        </div>

    @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            @icon('heroicon-o-credit-card', 'w-16 h-16 text-gray-300 dark:text-gray-600 mb-4')
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">No Active Subscription</h3>
            <p class="text-sm text-gray-500 mt-1 max-w-md">
                Your shop does not have an active subscription plan.
                Please contact support to activate your account.
            </p>
        </div>
    @endif
</x-filament-panels::page>
