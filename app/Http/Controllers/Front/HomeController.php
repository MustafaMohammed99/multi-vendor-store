<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Cookie;
use Stichoza\GoogleTranslate\GoogleTranslate;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with('category','wishlists:product_id')->active()
            ->latest()
            ->limit(8)
            ->get();

        return view('front.home', compact('products'));
    }
}
