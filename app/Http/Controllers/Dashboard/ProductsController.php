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
use App\Models\ProductImages;
use App\Models\TemporaryFile;

class ProductsController extends Controller
{
    use UploadImages;
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
        $product_images = new ProductImages();
        $categories = Category::all();
        $categories = $categories->pluck('name', 'id');
        $tags = '';
        return view('dashboard.products.create', compact('categories', 'product', 'tags', 'product_images'));
    }


    public function store(Request $request)
    {

        dd($request->all());
        // Gate::authorize('categories.create');
        $request->validate(Product::rules());
        $data = $request->except('tags', 'price', 'image');

        $image = $this->handleImageFilepond($request); // return json (path , url)
        $data['price'] = round($request->price);
        $data['image'] = $image;
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
        // $this->authorize('update', $product);

        $product = Product::with('images')->findOrFail($id);
        $product_images = $product->images->pluck('image')->toArray();
        $categories = Category::all();
        $categories = $categories->pluck('name', 'id');
        $tags = implode(',', $product->tags()->pluck('name')->toArray());

        return view('dashboard.products.edit', compact('product', 'tags', 'categories', 'product_images'));
    }


    public function update(Request $request, Product $product)
    {
        // $this->authorize('update', $product);

        $old_image = $product->image;
        $data = $request->except('image', 'tags');
        $data['store_id'] = 6;

        $new_image = $this->uploadImageGoogle($request, 'image', 'products');
        if ($new_image) {
            $data['image'] = $new_image;
        }

        $this->addImages($request, $product);

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

    public function handleImageFilepond($request)
    {
        // $request->post('image')  return json  contain (path , url) comming from filepond
        $temp_image_json_decode = json_decode($request->post('image'));
        // move image from temp to origin folder and delete temp
        $new_path =   'products/' . now()->timestamp . '_' . basename($temp_image_json_decode->path);
        Storage::disk('google')->move(
            $temp_image_json_decode->path,
            $new_path
        );

        Storage::disk('google')->delete($temp_image_json_decode->path);
        TemporaryFile::where('path', $temp_image_json_decode->path)->delete();

        return json_encode([
            'path' => $new_path,
            'url' => Storage::disk('google')->url($new_path)
        ]);
    }


    public function addProductImages($product_images, $product)
    {
        // $product_images array of json  contain object (path , url) comming from filepond
        if ($product_images) {
            foreach ($product_images as  $product_image) {
                $temp_image = json_decode($product_image);
                $new_path =    'product_images/' . now()->timestamp . '_' . basename($temp_image[0]->path);

                Storage::disk('google')->move(
                    $temp_image[0]->path,
                    $new_path
                );
                Storage::disk('google')->delete($temp_image[0]->path);
                TemporaryFile::where('path', $temp_image[0]->path)->delete();

                ProductImages::create([
                    'product_id' => $product->id,
                    'image' => [
                        'path' => $new_path,
                        'url' => Storage::disk('google')->url($new_path)
                    ]
                ]);
            }
        }
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
