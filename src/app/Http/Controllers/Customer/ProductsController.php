<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\Category;    
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request) 
    {
     $query = Product::with(['categories', 'media']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
             $q->where('categories.id', $request->category);
           });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->latest()->paginate(20)->appends($request->query());

        $tenant = tenant();
        $categories = Category::all(); 

        return view('customer.products.index', [
            'products'   => $products,
            'tenant'     => $tenant,
            'categories' => $categories 
        ]);
    }

    public function show($id)
    {
        $product = Product::with('category', 'reviews' , 'media')->findOrFail($id);
        $tenant = tenant();
        
        return view('customer.products.show', compact('product','tenant'));
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($id);

        $product->reviews()->create([
            'customer_name' => $request->customer_name,
            'rating'        => $request->rating,
            'comment'       => $request->comment,
        ]);
        
        return back()->with('success', 'Thank you for your review');
    }
}