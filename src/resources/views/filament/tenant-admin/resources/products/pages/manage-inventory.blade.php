<x-filament-panels::page>
    <x-filament::section :heading="__('Products Stock')">
        {{ $this->table }}
    </x-filament::section>

    <x-filament::section :heading="__('Stock Adjustment Requests')">
        @livewire(\App\Livewire\StockAdjustmentTable::class)
    </x-filament::section>

    <x-filament::section :heading="__('Suppliers')" collapsible>
        @livewire(\App\Livewire\SupplierTable::class)
    </x-filament::section>
</x-filament-panels::page>
