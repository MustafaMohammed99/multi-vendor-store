<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Concerns\UploadImages;
use App\Concerns\HandleTempImage;

use App\Models\ProductImages;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    use UploadImages, HandleTempImage;
    public function index()
    {
        // $this->authorize('view-any', Product::class);
        $request = request();
        $products = Product::with(['category', 'store'])
            ->filterBack($request->query())
            ->orderBy('products.name')
            ->paginate(7);

        return view('dashboard.products.index', compact('products'));
    }


    public function create()
    {
        // $this->authorize('create', Product::class);
        $product = new Product();
        $product_images = new ProductImages();
        $categories = Category::all();
        $categories = $categories->pluck('name', 'id');
        $tags = '';
        return view('dashboard.products.create', compact('categories', 'product', 'tags', 'product_images'));
    }


    public function store(Request $request)
    {
        // Gate::authorize('categories.create');
        $request->validate(Product::rules());
        $data = $request->except('tags', 'price', 'image');


        $data['image'] = $this->handleImageFilepond($request->image); // return json (path , url)
        $data['price'] = round($request->price);
        $data['store_id'] = Auth::user()->store_id;

        $product = Product::create($data);

        $this->addTags($request, $product);
        $this->addProductImages($request->product_images, $product);

        return Redirect::route('dashboard.products.index')
            ->with('success', 'Proudct created!');
    }


    public function show($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $this->authorize('view', $product);
    }


    public function edit($id)
    {
        $product = Product::with('tags', 'images')->findOrFail($id);
        $product_images = $product->images->pluck('image_url', 'image_path')->toArray();

        $categories = Category::all();
        $categories = $categories->pluck('name', 'id');
        $tags = implode(',', $product->tags()->pluck('name')->toArray());

        return view('dashboard.products.edit', compact('product', 'tags', 'categories', 'product_images'));
    }


    public function update(Request $request, Product $product)
    {
        // $this->authorize('update', $product);

        $data = $request->except('image', 'tags');
        $image = $this->handleImageFilepond($request->image); // return json (path , url)

        if ($image != null)
            $data['image'] = $image;

        $data['store_id'] = Auth::user()->store_id;
        $product->update($data);

        $this->addTags($request, $product);
        $this->addProductImages($request->product_images, $product);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product updated' . $product->name);
    }


    public function addTags($request, $product)
    {
        $tags = json_decode($request->post('tags'));
        $tag_ids = [];
        $saved_tags = Tag::all();
        if ($tags)
            foreach ($tags as $item) {
                $slug = Str::slug($item->value);
                $tag = $saved_tags->where('slug', $slug)->first();
                if (!$tag) {
                    $tag = Tag::create([
                        'name' => $item->value,
                        'slug' => $slug,
                    ]);
                }
                $tag_ids[] = $tag->id;
            }
        $product->tags()->sync($tag_ids);
    }






    public function destroy(Product $product)
    {
        // $this->authorize('delete', $product);

        $product->delete();
        return Redirect::route('dashboard.products.index')
            ->with('success', 'Proudct deleted!');
    }



    public function trash()
    {
        $request = request();
        $products = Product::with(['category', 'store'])
            ->filterBack($request->query())
            ->orderBy('products.name')
            ->onlyTrashed()->paginate();
        return view('dashboard.products.trash', compact('products'));
    }

    public function restore(Request $request, $id)
    {
        $category = Product::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('dashboard.products.trash')
            ->with('succes', 'Product restored!');
    }

    public function forceDelete($id)
    {
        $category = Product::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        if ($category->image) {
            Storage::disk('uploads')->delete($category->image);
        }

        return redirect()->route('dashboard.products.trash')
            ->with('succes', 'Product deleted forever!');
    }
}
