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

    protected $appends = [
        'image_url',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }


    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://www.incathlab.com/images/products/default_product.png';
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        return asset('uploads/' . $this->image);
    }
}
