<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

new class extends Component
{
    use WithPagination;

    public function render()
    {
        $products = Product::paginate(12); 

        return view('pages.products.index', [
            'products' => $products
        ]);
    }
};
?>

<div>
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @forelse($products as $product)
            <livewire:product :product="$product"/>
        @empty
            <div class="col-span-full text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                <p class="text-2xl font-bold text-gray-400">لا توجد منتجات متوفرة حالياً.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12 flex justify-center">
        {{ $products->links() }}
    </div>
</div>
</div>