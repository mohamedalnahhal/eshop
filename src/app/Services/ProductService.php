<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function getFiltered(array $filters): LengthAwarePaginator
    {
        $locale = app()->getLocale();

        return Product::query()
            ->withTranslation($locale)
            ->with(['categories', 'media'])
            ->when($filters['category_id'] ?? null,
                fn($q, $v) => $q->whereHas('categories', fn($q) => $q->where('categories.id', $v))
            )
            ->when($filters['min_price'] ?? null,
                fn($q, $v) => $q->where('price', '>=', $v)
            )
            ->when($filters['max_price'] ?? null,
                fn($q, $v) => $q->whereRaw('price <= ?', [(int)$v + 0.99])
            )
            ->when($filters['search'] ?? null,
                fn($q, $v) => $q->whereTranslation('name', 'like', "%{$v}%", $locale)
            )
            ->when($filters['sort'] ?? 'latest', function ($q, $sort) {
                return match($sort) {
                    'price_asc'  => $q->orderBy('price'),
                    'price_desc' => $q->orderByDesc('price'),
                    'top_rated'  => $q->orderByDesc('avg_rating'),
                    default      => $q->latest(),
                };
            })
            ->paginate(12);
    }

    public function getPriceRange(?string $categoryId = null): array
    {
        $query = Product::query();

        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        return [
            'min' => $query->min('price'),
            'max' => $query->max('price'),
        ];
    }
}