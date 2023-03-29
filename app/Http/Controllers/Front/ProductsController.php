<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
    }

    public function show(Product $product)
    {

        // dd($product->image_url);
        if ($product->status != 'active') {
            abort(404);
        }

        $product = $product->load('images');

        return view('front.products.show', compact('product'));
    }
}
