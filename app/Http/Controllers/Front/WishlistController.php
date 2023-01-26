<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;

use App\Http\Requests\StoreWishlistRequest;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wishlists = Wishlist::with('product')->get();
        return view('front.wishlist', compact('wishlists'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreWishlistRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWishlistRequest $request)
    {
        $product = Product::findOrFail($request->post('product_id'));
        $item =  Wishlist::where('product_id', '=', $product->id)->first();

        if (!$item) {
            $wishlist = Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);
            return $wishlist;
        } else {
            $item->delete();
        }

        if ($request->expectsJson()) {

            return response()->json([
                'message' => 'Item added to Wishlist!',
            ], 201);
        }

        return redirect()->route('Wishlist.index')
            ->with('success', 'Product added to Wishlist!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wishlist  $Wishlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wishlist $wishlist)
    {
        $wishlist->delete();
    }
}
