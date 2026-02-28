<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
        $product = Product::findOrFail($id);
        return view('customer.products.show', compact('product'));
    }
}