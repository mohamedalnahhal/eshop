<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index() 
    {
        $products = Product::with('category')
              ->latest()
              ->paginate(20);

        $tenant = tenant(); 

        return view('customer.products.index', [
            'products' => $products,
            'tenant'   => $tenant
        ]);
    }

    public function show($id)
    {
        $product = Product::with('category', 'reviews')->findOrFail($id);
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