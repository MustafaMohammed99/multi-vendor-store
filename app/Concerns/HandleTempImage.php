<?php

namespace App\Concerns;

use App\Models\ProductImages;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\Storage;

trait HandleTempImage
{

    // parmiter contain (path , url)
    // get image from temp
    // rename image by function move
    // delete from temp table TemporaryFile
    // return image  = json  contain new (path , url)
    public function handleImageFilepond($image)
    {
        // $image  return json  contain (path , url) comming from filepond
        $temp_image_json_decode = json_decode($image);
        if ($temp_image_json_decode) {

            $path = $temp_image_json_decode->path;
            $new_path = $this->rename_file_temp($path);

            // Rename the file using Laravel's storage
            Storage::disk('google')->move($path, $new_path);
            TemporaryFile::where('path', $path)->delete();

            return json_encode([
                'path' => $new_path,
                'url' => Storage::disk('google')->url($new_path)
            ]);
        }
    }


    // get images from temp
    // rename images by function move
    // delete from temp table TemporaryFile
    // add new images to table multi image (ProductImages)
    public function addProductImages($product_images, $product)
    {
        // $product_images array of json  contain object (path , url) comming from filepond
        if ($product_images) {
            foreach ($product_images as  $product_image) {
                $temp_image = json_decode($product_image);

                if ($temp_image) {
                    $path = $temp_image[0]->path;
                    $new_path = $this->rename_file_temp($path);
                    Storage::disk('google')->move($path, $new_path);
                    TemporaryFile::where('path', $path)->delete();

                    ProductImages::create([
                        'product_id' => $product->id,
                        'image' => json_encode([
                            'path' => $new_path,
                            'url' => Storage::disk('google')->url($new_path)
                        ])
                    ]);
                }
            }
        }
    }


    private function rename_file_temp($path)
    {
        // Get the directory name from the path
        $dirname = pathinfo($path, PATHINFO_DIRNAME);

        // Get the filename from the path
        $filename = pathinfo($path, PATHINFO_BASENAME);

        // Remove the 'temp_' prefix from the filename
        $newFilename = str_replace('temp_', '', $filename);

        // Generate the new path
        return $dirname . '/' . $newFilename;
    }
}
