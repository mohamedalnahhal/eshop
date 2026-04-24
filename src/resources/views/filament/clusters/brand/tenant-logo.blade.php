<div class="flex items-center gap-2 group">
    <img src="{{ tenant('logo_url') ? asset('storage/' . tenant('logo_url')) : asset('images/logo.svg') }}" alt="eShop" class="h-8 w-auto">
    @guest
    <span class="text-xl font-semibold text-slate-900">{{ tenant('name') ?? 'eShop\'s Shop' }}</span>
    @else
    <span class="text-xl font-semibold text-slate-900">{{ tenant('name') ?? 'Your Shop' }}</span>
    <span class="inline-flex items-center gap-1.5 ms-2 px-2 py-1 rounded-lg text-sm font-semibold bg-slate-200 text-slate-600">Dashboard</span>
    @endguest
</div>