<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

use App\Concerns\UploadImages;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    use  UploadImages;

    public function store(Request $request)
    {
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
        if ($image) {
            TemporaryFile::create(['path' => $path]);
            return $image;
        }
        return '';
    }


    public function revert(Request $request)
    {
        $image = trim(stripslashes($request->getContent()), '"');
        $image = json_decode($image);

        //  is array the image comming from input multible
        if (is_array($image)) {
            $path = $image[0]->path;
        } else {
            $path = $image->path;
        }

        Storage::disk('google')->delete($path);
        TemporaryFile::where('path', $path)->delete();


        return [
            'path' => $image,
        ];
        // return [
        //     'success' =>' deleted image',
        // ];
    }


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
