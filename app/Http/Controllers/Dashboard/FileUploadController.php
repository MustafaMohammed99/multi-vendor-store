<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

use App\Concerns\UploadImages;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\TemporaryFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    use  UploadImages;

    public function store(Request $request)
    {
        $name_file_in_form = $request->name;
        $type_file_in_form = $request->type; // type image multiple or single
        $folder_place = Carbon::now()->year . '/' . Carbon::now()->month . '/' . $request->name_folder;

        if ($request->hasFile($name_file_in_form)) {
            if ($type_file_in_form === 'single') {
                // upload Image to Google return json contain (path , url)
                $image = $this->uploadImageGoogle($request, $name_file_in_form, $folder_place);
                $path = $image['path'];
            } elseif ($type_file_in_form === 'multiple') {
                // upload Images to Google return array of json contain (path , url)
                $image = $this->uploadImagesGoogle($request, $name_file_in_form, $folder_place);
                $path = $image[0]['path'];
            }
        }

        if ($image) {
            TemporaryFile::create(['path' => $path]);
            return $image;
        }
        return '';
    }

    public function revert(Request $request)
    {
        // الان في الفايل بوند لما تيجي تنشأها في التصميم
        // اذا عرفت الاوبشن طبيعي هيجي هان اسم الصورة في الفورم وبتتعامل معاه
        //اذا عرفت الاوبشن كستم هيجي هان اللي انتات بترجعه من الكستم

        $detail = json_decode($request->getContent()); // deetain contain path and url image
        if (is_array($detail)) {
            $path = $detail[0]->path;
        } else {
            $path = $detail->path;
        }

        Storage::disk('google')->delete($path);
        TemporaryFile::where('path', $path)->delete();

        return [
            'success' => ' deleted image',
        ];
    }

    // when singel image is update,  record image not can delete record because contain anathour data
    // when multiple delete image because record is contain only data image
    public function delete_image(Request $request)
    {
        $model = $request->model;
        $type = $request->type;
        $id = $request->id;
        $image_path = $request->image_path;

        $is_operate = false;
        if ($model === 'product') {
            if ($type === 'single') {
                $product = Product::find($id);
                $is_operate = $product->update(['image' => json_encode([])]);
            } elseif ($type === 'multiple') {
                $product_images = ProductImages::where('product_id', $id)->get();
                foreach ($product_images as $product_image) {
                    if ($product_image->image_path === $image_path) {
                        $is_operate =  $product_image->delete();
                        break;
                    }
                }
            }
        } elseif ($model === 'category') {
            $category = Category::findOrFail($id);
            $is_operate = $category->update(['image' => json_encode([])]);
        }


        if ($is_operate) {
            Storage::disk('google')->delete($image_path);
            return [
                'status' => 'success',
            ];
        }
        return $request->all();
    }


    /*
        if ($request->hasFile('image')) {
            // uploadImageGoogle return json contain (path , url)
            $image = $this->uploadImageGoogle($request, 'image', 'temp');
            $path = $image['path'];
        }
        if ($request->hasFile('product_images')) {
            // uploadImagesGoogle return array of json contain (path , url)
            $image = $this->uploadImagesGoogle($request, 'product_images', 'temp');
            $path = $image[0]['path'];
        }
        return $image;
        if ($image) {
            TemporaryFile::create(['path' => $path]);
            return $image;
        }
        return '';
    */


    // public function rules($request, $name_in_form)
    // {
    //     return 'ss';
    // $validator = Validator::make($request->all(), [
    //     "image"  => 'required|image|max:100', //max size is 2MB
    //     // add more fields and validation rules as needed
    // ]);

    // $validator->validate();
    // if ($validator->fails()) {
    //     return response()->json(['errors' => $validator->errors()]);
    // }
    // }

    public $rules = [
        "image"  => 'required|image|max:100', //max size is 2MB
    ];
}
