<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

use App\Concerns\UploadImages;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Concerns\HandleTempImage;
use App\Http\Requests\StoreRequest;
use Illuminate\Support\Facades\Hash;

class StoresController extends Controller
{
    use UploadImages, HandleTempImage;

    public function __construct()
    {
        $this->authorizeResource(Admin::class, 'store');
    }


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
            ->paginate(3);

        return view('dashboard.stores.index', compact('stores'));
    }



    public function create()
    {
        $store = new Store();
        $user = new User();
        $showPassword = true;
        return view('dashboard.stores.create', compact('store', 'user', 'showPassword'));
    }


    public function store(StoreRequest $request)
    {
        $request->merge(['user_type' => 'super-admin']);
        $request->validate(User::rules());

        DB::beginTransaction();
        try {

            $data = $request->except('logo_image', 'cover_image');

            $data['logo_image']  = $this->handleImageFilepond($request->logo_image); // return json (path , url)
            $data['cover_image']  = $this->handleImageFilepond($request->cover_image); // return json (path , url)

            Store::create($data);

            User::create([
                'name' => $request->user_name,
                'phone_number' => $request->user_phone_number,
                'email' => $request->user_email,
                'type' => 'super-admin',
                'password' => Hash::make('password'),
            ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return Redirect::route('dashboard.stores.index')
            ->with('success', 'Store created!');
    }

    public function edit(Store $store)
    {
        // if (Auth::user()->id !== $user->id) {
        // if (Auth::user()->type !== 'admin') {
        //     Gate::authorize('users.update');
        // }
        // }

        $showPassword = false;
        return view('dashboard.stores.edit', compact('store', 'user', 'showPassword'));
    }


    public function show(Store $store)
    {
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
            Storage::disk('uploads')->delete($store->logo_image);
        }
        if ($store->cover_image) {
            Storage::disk('uploads')->delete($store->cover_image);
        }

        return redirect()->route('dashboard.stores.trash')
            ->with('succes', 'Store deleted forever!');
    }
}
