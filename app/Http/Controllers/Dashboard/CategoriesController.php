<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Concerns\HandleTempImage;
use App\Concerns\UploadImages;
use App\Models\TemporaryFile;

class CategoriesController extends Controller
{
    use UploadImages, HandleTempImage;
    public function index()
    {
        $request = request();
        $categories = Category::with('parent')->withCount([
            'products' => function ($query) {
                $query->where('status', '=', 'active');
            }
        ])
            ->filter($request->query())
            ->orderBy('categories.name')
            ->paginate(7);

        // dd($categories->first()->image_url);
        return view('dashboard.categories.index', compact('categories'));
    }

    public function create()
    {
        // if (Gate::denies('categories.create')) {
        // abort(403);
        // }

        $parents = Category::all();
        $category = new Category();
        return view('dashboard.categories.create', compact('category', 'parents'));
    }


    public function store(CategoryRequest $request)
    {
        $data = $request->except('image');
        $data['image'] = $this->handleImageFilepond($request->image); // return json (path , url)

        Category::create($data);

        return Redirect::route('dashboard.categories.index')
            ->with('success', 'Category created!');
    }


    public function show(Category $category)
    {
        // if (Gate::denies('categories.view')) {
        // abort(403);
        // }
        $products = $category->products()->with('store')->latest()->paginate(5);

        return view('dashboard.categories.show', [
            'category' => $category,
            'products' => $products
        ]);
    }


    public function edit($id)
    {
        // Gate::authorize('categories.update');

        try {
            $category = Category::findOrFail($id);
        } catch (Exception $e) {
            return redirect()->route('dashboard.categories.index')
                ->with('info', 'Record not found!');
        }

        // SELECT * FROM categories WHERE id <> $id
        // AND (parent_id IS NULL OR parent_id <> $id)
        $parents = Category::where('id', '<>', $id)
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id')
                    ->orWhere('parent_id', '<>', $id);
            })
            ->get();

        return view('dashboard.categories.edit', compact('category', 'parents'));
    }


    public function update(CategoryRequest $request,  Category $category)
    {
        $data = $request->except('image');
        $image = $this->handleImageFilepond($request->image); // return json (path , url)

        if ($image != null)
            $data['image'] = $image;

        $category->update($data);

        return Redirect::route('dashboard.categories.index')
            ->with('success', 'Category updated!');
    }




    public function destroy(Category $category)
    {
        // Gate::authorize('categories.delete');
        $category->delete();
        return Redirect::route('dashboard.categories.index')
            ->with('success', 'Category deleted!');
    }

    public function trash()
    {
        $request = request();
        $categories = Category::with('parent')->withCount([
            'products' => function ($query) {
                $query->where('status', '=', 'active');
            }
        ])
            ->filter($request->query())
            ->orderBy('categories.name')
            ->onlyTrashed()->paginate();
        return view('dashboard.categories.trash', compact('categories'));
    }

    public function restore(Request $request, $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('dashboard.categories.trash')
            ->with('succes', 'Category restored!');
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        if ($category->image) {
            Storage::disk('uploads')->delete($category->image);
        }

        return redirect()->route('dashboard.categories.trash')
            ->with('succes', 'Category deleted forever!');
    }
}
