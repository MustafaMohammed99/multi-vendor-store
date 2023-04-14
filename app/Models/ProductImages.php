<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductImages extends Model
{

    protected $table = 'product_images';

    use HasFactory;

    protected $fillable = [
        'product_id', 'image'
    ];

    protected $casts = [
        'image' => 'json',
    ];

    protected $appends = [
        'image_url',
        'image_path',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }


      // $product->image_url
      public function getImageUrlAttribute()
      {
          $google_image = json_decode($this->image);
          if ($google_image !== null && isset($google_image->url)) {
              return $google_image->url;
          }
          return 'https://www.incathlab.com/images/products/default_product.png';
      }


      public function getImagePathAttribute()
      {
          $google_image = json_decode($this->image);
          if ($google_image !== null && isset($google_image->path)) {
              return $google_image->path;
          }
          return null;
      }


}
