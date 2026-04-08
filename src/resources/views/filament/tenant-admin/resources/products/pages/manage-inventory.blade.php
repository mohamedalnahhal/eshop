<x-filament-panels::page>

    {{-- الجدول الأول: المخزون --}}
    <x-filament::section :heading="__('Products Stock')">
        {{ $this->table }}
    </x-filament::section>

    {{-- الجدول الثاني: سجل التعديلات كـ Component منفصل --}}
    <x-filament::section :heading="__('Stock Adjustment Requests')">
        @livewire(\App\Livewire\StockAdjustmentTable::class)
    </x-filament::section>

</x-filament-panels::page>