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

use App\Concerns\UploadImages;

class CategoriesController extends Controller
{
    use UploadImages;

    public function index()
    {
        // if (!Gate::allows('categories.view')) {
        //     abort(403);
        // }

        $request = request();
        $categories = Category::with('parent')->withCount([
            'products' => function ($query) {
                $query->where('status', '=', 'active');
            }
        ])
            ->filter($request->query())
            ->orderBy('categories.name')
            ->paginate();

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


    public function store(Request $request)
    {
        // Gate::authorize('categories.create');

        $request->validate(Category::rules());

        $data = $request->except('image');
        $data['image'] = $this->uploadImageGoogle($request,  'image', 'categories');
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


    public function update(CategoryRequest $request, $id)
    {
        $request->validate(Category::rules($id));

        $category = Category::findOrFail($id);
        $old_image = $category->image;

        $data = $request->except('image');
        $new_image = $this->uploadImageGoogle($request, 'image', 'categories');
        if ($new_image) {
            $data['image'] = $new_image;
        }
        $category->update($data);
        if ($old_image && $new_image) {
            Storage::disk('uploads')->delete($old_image);
        }

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
