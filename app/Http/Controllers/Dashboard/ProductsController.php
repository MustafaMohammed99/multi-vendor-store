<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\ImportProducts;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsController extends Controller
{

    public function index()
    {
        // $this->authorize('view-any', Product::class);
        $request = request();
        $products = Product::with(['category', 'store'])
            ->filterBack($request->query())
            ->orderBy('products.name')
            ->paginate();

        return view('dashboard.products.index', compact('products'));
    }


    public function create()
    {
        // $this->authorize('create', Product::class);
        $product = new Product();
        $categories = Category::all();
        $categories = $categories->pluck('name', 'id');
        $tags = '';
        return view('dashboard.products.create', compact('categories', 'product', 'tags'));
    }


    public function store(Request $request)
    {
        // Gate::authorize('categories.create');
        $request->validate(Product::rules());

        $data = $request->except('image', 'tags');
        $data['image'] = $this->uploadImgae($request);
        $data['store_id'] = 6;
        $product = Product::create($data);
        $this->addTags($request, $product);

        return Redirect::route('dashboard.products.index')
            ->with('success', 'Proudct created!');
    }


    public function show($id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('view', $product);
    }


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        // $this->authorize('update', $product);
        $categories = Category::all();
        $categories = $categories->pluck('name', 'id');
        $tags = implode(',', $product->tags()->pluck('name')->toArray());

        return view('dashboard.products.edit', compact('product', 'tags', 'categories'));
    }


    public function update(Request $request, Product $product)
    {
        // $this->authorize('update', $product);

        $old_image = $product->image;
        $data = $request->except('image', 'tags');
        $data['store_id'] = 6;

        $new_image = $this->uploadImgae($request);
        if ($new_image) {
            $data['image'] = $new_image;
        }

        $product->update($data);
        $this->addTags($request, $product);


        if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
        }

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product updated');
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
            Storage::disk('public')->delete($category->image);
        }

        return redirect()->route('dashboard.products.trash')
            ->with('succes', 'Product deleted forever!');
    }


    protected function uploadImgae(Request $request)
    {
        if (!$request->hasFile('image')) {
            return;
        }

        $file = $request->file('image'); // UploadedFile Object

        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);
        return $path;
    }
}
