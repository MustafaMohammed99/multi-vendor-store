<?php

namespace App\Concerns;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

trait UploadImages
{

    public function uploadImage($request, $name_image_inForm, $folder_place = 'other', $name_disk = 'google')
    {
        if (!$request->hasFile($name_image_inForm)) {
            return;
        }
        if ($request->hasFile($name_image_inForm)) {
            $file = $request->file($name_image_inForm);
            if ($file->isValid()) {
                $path = $file->store($folder_place, [
                    'disk' => $name_disk,
                ]);
            }
        }
        if ($path) {
            if ($name_disk === 'google') {
                $path = Storage::disk('google')->url($path);
            }
            return $path;
        }
        return;
    }

    public function uploadImages($request, $name_image_inForm, $folder_place = 'other', $name_disk = 'google')
    {
        if (!$request->hasFile($name_image_inForm)) {
            return;
        }

        $files = $request->file($name_image_inForm);
        $images_product = [];
        foreach ($files as $file) {
            if ($file->isValid()) {
                $path = $file->store($folder_place, [
                    'disk' => $name_disk,
                ]);

                if ($path) {
                    if ($name_disk === 'google') {
                        $path = Storage::disk('google')->url($path);
                    }
                    $images_product[] = $path;
                }
            }
        }

        return $images_product;
    }
}

