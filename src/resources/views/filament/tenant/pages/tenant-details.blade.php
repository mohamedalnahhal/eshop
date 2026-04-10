<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>{{ number_format($this->getRecord()->orders()->where('status', 'completed')->sum('total_price'), 2) }} ₪</div>
        <div>{{ $this->getRecord()->orders()->count() }}</div>
        <div>{{ $this->getRecord()->products()->count() }}</div>
        <div>{{ $this->getRecord()->users()->count() }}</div>
    </div>
</x-filament-panels::page>