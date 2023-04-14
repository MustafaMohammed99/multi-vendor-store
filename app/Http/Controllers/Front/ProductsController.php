<?php

namespace App\Http\Controllers\Front;

use App\Helpers\Currency;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductsController extends Controller
{
    public function index()
    {
        // $filter_price = Product::filterPrice()->get();

        $converter = app('currency.converter'); // بيروح يجيب الاوبجكت من السيرفس البروفايدر
        $base_currency = config('app.currency');
        $currency_to = Session::get('currency_code', $base_currency);
        $rate = $converter->convert($base_currency, $currency_to);

        $price_ranges = Product::GetPriceRanges($rate)->get(); // containn array values  products_count  and price_range

        $categories = Category::with('parent')->withCount('products')->get();


        $categories_selected = request()->query('categories');
        $price_ranges_selected = request()->query('price_ranges');

        $products = Product::with('wishlists:product_id')->FilteredProducts(
            $categories_selected,
            $price_ranges_selected,
            request()->query('search'),
            request()->query('sorting')
        )->paginate(4);


        return view('front.products.index', compact('products', 'price_ranges', 'categories', 'categories_selected', 'price_ranges_selected'));
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
