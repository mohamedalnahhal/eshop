<x-filament-panels::page>
    <div class="w-full flex flex-col lg:flex-row gap-6">

        <div class="w-full lg:flex-1 min-w-0">
            {{ $this->table }}
        </div>

        <div class="w-full lg:w-72 shrink-0">
            <x-filament::section
                heading="Suppliers"
                icon="heroicon-o-truck"
                :contained="false"
            >
                @livewire(\App\Livewire\SupplierTable::class)
            </x-filament::section>
        </div>

    </div>
    <x-filament-actions::modals />
</x-filament-panels::page>