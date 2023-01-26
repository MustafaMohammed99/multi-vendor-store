<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class StoresController extends Controller
{

    public function index()
    {
        $request = request();
        $stores = Store::with('products')->withCount([
            'products' => function ($query) {
                $query->where('status', '=', 'active');
            }
        ])
            ->filter($request->query())
            ->orderBy('stores.name')
            ->paginate();

        return view('dashboard.stores.index', compact('stores'));
    }


    public function show(Store $store)
    {
        // if (Gate::denies('categories.view')) {
        // abort(403);
        // }
        $products = Product::where('store_id', '=', $store->id)
            ->with('category')
            ->latest()->paginate();

        return view('dashboard.stores.show', [
            'store' => $store,
            'products' => $products,
        ]);
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return Redirect::route('dashboard.stores.index')
            ->with('success', 'Store deleted!');
    }


    public function trash()
    {
        $request = request();
        $stores = Store::withCount([
            'products' => function ($query) {
                $query->where('status', '=', 'active');
            }
        ])
            ->filter($request->query())
            ->orderBy('stores.name')
            ->onlyTrashed()->paginate();

        // $stores = Store::onlyTrashed()->paginate();
        return view('dashboard.stores.trash', compact('stores'));
    }

    public function restore(Request $request, $id)
    {
        $store = Store::onlyTrashed()->findOrFail($id);
        $store->restore();

        return redirect()->route('dashboard.stores.trash')
            ->with('succes', 'Store restored!');
    }

    public function forceDelete($id)
    {
        $store = Store::onlyTrashed()->findOrFail($id);
        $store->forceDelete();

        if ($store->logo_image) {
            Storage::disk('public')->delete($store->logo_image);
        }
        if ($store->cover_image) {
            Storage::disk('public')->delete($store->cover_image);
        }

        return redirect()->route('dashboard.stores.trash')
            ->with('succes', 'Store deleted forever!');
    }
}
