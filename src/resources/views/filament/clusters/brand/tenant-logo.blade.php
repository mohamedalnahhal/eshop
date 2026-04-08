<div class="flex flex-row items-center justify-center gap-2 text-primary-900">
    <img class="h-8" src="{{ tenant('logo_url') ? asset('storage/' . tenant('logo_url')) : asset('images/logo.svg') }}" alt="eShop">
    <span>{{ tenant('name') ?? 'eShop Store' }}</span>
</div>