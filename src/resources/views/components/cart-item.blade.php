@props(['item', 'subtotal'])

<div class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row items-center gap-4 hover:shadow-md transition">
    <div class="flex flex-row gap-4 min-w-0 grow">
        <a href="{{ route('shop.product.show', ['id' => $item->product->id]) }}" class="block grow aspect-square relative overflow-hidden min-w-18 h-18 sm:min-w-24 sm:h-24 rounded-lg items-center justify-center">
            @php
                $imagePath = $item->product->media->first()?->file_path;
            @endphp
            
            @if($imagePath)
                <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
            @else
                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 font-bold">
                    No Image
                </div>
            @endif
        </a>

        <div class="grow min-w-0">
            <h3 class="mb-1 line-clamp-1">
                <a href="{{ route('shop.product.show', ['id' => $item->product->id]) }}" class="block text-xl font-bold text-gray-800 hover:underline">
                    {{ $item->product->name }}
                </a>
            </h3>
            <p class="text-gray-500 text-sm mb-2 line-clamp-1">
                {{ $item->product->description ? Str::limit($item->product->description, 60) : 'لا يوجد وصف متاح لهذا المنتج حالياً.' }}
            </p>
            <p>
                <span class="text-xl font-black text-green-600">
                    ${{ number_format($subtotal, 2) }}
                </span>
                <span class="text-gray-500 text-sm font-semibold whitespace-nowrap">سعر الوحدة: ${{ number_format($item->price, 2) }}</span>
            </p>
            
        </div>
    </div>

    <div class="sm:pe-2 shrink-0 flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto pt-4 sm:pt-0 border-t border-gray-200 sm:border-none">
        <button wire:click="deleteItem('{{ $item->id }}')"
            wire:loading.attr="disabled"
            wire:target="deleteItem('{{ $item->id }}')"
            type="button"
            class="border border-gray-200 hover:border-red-200 hover:text-red-700 hover:bg-red-100 p-2 rounded-lg transition cursor-pointer" title="حذف المنتج من السلة">
            <span wire:loading.remove wire:target="deleteItem('{{ $item->id }}')">🗑️</span>
            
            <x-spinner wire:loading wire:target="deleteItem('{{ $item->id }}')" class="h-5 w-5" />

            <span class="sm:hidden" wire:loading.remove wire:target="deleteItem('{{ $item->id }}')">حذف</span>
            <span class="sm:hidden text-red-600 font-bold" wire:loading wire:target="deleteItem('{{ $item->id }}')">جاري...</span>
        </button>
        <div class="flex flex-row sm:flex-col gap-1">
            <button wire:click="incrementItem('{{ $item->id }}')" 
                    wire:loading.attr="disabled"
                    wire:target="incrementItem('{{ $item->id }}')"
                    class="flex items-center justify-center rounded-full max-sm:w-10 sm:h-6 font-bold border border-gray-200 hover:bg-gray-200 hover:border-gray-300 transition active:scale-95 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="incrementItem('{{ $item->id }}')">+</span>
                <x-spinner wire:loading wire:target="incrementItem('{{ $item->id }}')" class="h-3 w-3" />
            </button>
            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 sm:py-1 text-sm font-bold text-gray-700">
                <span class="sm:hidden">الكمية : </span>
                {{ $item->quantity }}
            </div>
            <button wire:click="decrementItem('{{ $item->id }}')" 
                    wire:loading.attr="disabled"
                    wire:target="decrementItem('{{ $item->id }}')"
                    class="flex items-center justify-center rounded-full max-sm:w-10 sm:h-6 font-bold border border-gray-200 hover:bg-gray-200 hover:border-gray-300 transition active:scale-95 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="decrementItem('{{ $item->id }}')">-</span>
                <x-spinner wire:loading wire:target="decrementItem('{{ $item->id }}')" class="h-3 w-3" />
            </button>
        </div>
    </div>
</div>