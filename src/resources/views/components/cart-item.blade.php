@props(['item', 'subtotal'])

<div class="p-4 card flex flex-col sm:flex-row gap-4">
    <div class="flex flex-row gap-4 min-w-0 grow">
        <a href="{{ route('shop.product.show', ['id' => $item->product->id]) }}" class="block grow aspect-square relative overflow-hidden min-w-18 max-w-18 w-18 h-18 sm:min-w-24 sm:max-w-24 sm:w-24 sm:h-24 rounded-[calc(var(--radius-card)-0.25rem)] items-center justify-center">
            @php
                $imagePath = $item->product->media->first()?->file_path;
            @endphp
            
            @if($imagePath)
                <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
            @else
                <div class="w-full h-full bg-surface-200 flex items-center justify-center text-muted font-bold">
                    @icon('image', 'w-7 h-7')
                </div>
            @endif
        </a>

        <div class="grow min-w-0">
            <h3 class="mb-1 line-clamp-1">
                <a href="{{ route('shop.product.show', ['id' => $item->product->id]) }}" class="block text-xl font-bold text-theme hover:underline">
                    {{ $item->product->name }}
                </a>
            </h3>
            <p class="text-muted text-sm mb-2 line-clamp-1">
                {{ $item->product->description ? Str::limit($item->product->description, 60) : 'لا يوجد وصف متاح لهذا المنتج حالياً.' }}
            </p>
            <p>
                <span class="text-xl font-black text-accent">
                    ${{ number_format($subtotal, 2) }}
                </span>
                <span class="text-muted text-sm font-semibold whitespace-nowrap">سعر الوحدة: ${{ number_format($item->price, 2) }}</span>
            </p>
            
        </div>
    </div>

    <div class="sm:pe-2 shrink-0 flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto pt-4 sm:pt-0 border-t border-border sm:border-none">
        <button wire:click="deleteItem('{{ $item->id }}')"
            wire:loading.attr="disabled"
            wire:target="deleteItem('{{ $item->id }}')"
            type="button"
            class="flex flex-row gap-1 items-center border border-border-input hover:border-danger/40 hover:text-danger hover:bg-danger/10 p-2 rounded-icon transition cursor-pointer" title="حذف المنتج من السلة">
            {{-- <span >🗑️</span> --}}
            <div wire:loading.remove wire:target="deleteItem('{{ $item->id }}')">
                @icon('trash', 'w-5 h-5')
            </div>
            
            <x-spinner wire:loading wire:target="deleteItem('{{ $item->id }}')" class="h-5 w-5" />

            <span class="sm:hidden" wire:loading.remove wire:target="deleteItem('{{ $item->id }}')">حذف</span>
            <span class="sm:hidden text-danger font-bold" wire:loading wire:target="deleteItem('{{ $item->id }}')">جاري...</span>
        </button>
        <div class="flex flex-row sm:flex-col gap-1">
            <button wire:click="incrementItem('{{ $item->id }}')" 
                    wire:loading.attr="disabled"
                    wire:target="incrementItem('{{ $item->id }}')"
                    class="flex items-center justify-center rounded-input-full max-sm:w-10 sm:h-6 font-bold border border-border-input hover:bg-surface-200 hover:border-border transition active:scale-95 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="incrementItem('{{ $item->id }}')">+</span>
                <x-spinner wire:loading wire:target="incrementItem('{{ $item->id }}')" class="h-3 w-3" />
            </button>
            <div class="border border-border-input rounded-icon px-4 py-2 sm:py-1 text-sm font-bold text-theme">
                <span class="sm:hidden">الكمية : </span>
                {{ $item->quantity }}
            </div>
            <button wire:click="decrementItem('{{ $item->id }}')" 
                    wire:loading.attr="disabled"
                    wire:target="decrementItem('{{ $item->id }}')"
                    class="flex items-center justify-center rounded-input-full max-sm:w-10 sm:h-6 font-bold border border-border-input hover:bg-surface-200 hover:border-border transition active:scale-95 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="decrementItem('{{ $item->id }}')">-</span>
                <x-spinner wire:loading wire:target="decrementItem('{{ $item->id }}')" class="h-3 w-3" />
            </button>
        </div>
    </div>
</div>