<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'parent_id', 'description', 'image', 'status', 'slug'
    ];

    protected $casts = [
        'image' => 'json',
    ];

    protected $appends = [
        'image_url',
        'image_path',
        'name_translate',
        'description_translate',

    ];


    protected static function booted()
    {
        static::creating(function (Category $category) {
            $category->slug = Str::slug($category->name);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id')
            ->withDefault([
                'name' => '-'
            ]);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', '=', 'active');
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['name'] ?? false, function ($builder, $value) {
            $builder->where('categories.name', 'LIKE', "%{$value}%");
        });

        $builder->when($filters['status'] ?? false, function ($builder, $value) {
            $builder->where('categories.status', '=', $value);
        });
    }


    // Accessors image_url NameTranslate ImageUrl
    public function getImageUrlAttribute()
    {
        // return($this->image);
        $google_image = json_decode($this->image);
        // $google_image = is_string($this->image) ? json_decode($this->image) : $this->image;

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


    public function getNameTranslateAttribute()
    {
        if (app()->getLocale() == 'ar') {
            $translate = new GoogleTranslate();
            return  $translate->setSource('en')->setTarget('ar')->translate($this->name);
        }

        return $this->name;
    }

    public function getDescriptionTranslateAttribute()
    {
        if (app()->getLocale() == 'ar') {
            $translate = new GoogleTranslate();
            return  $translate->setSource('en')->setTarget('ar')->translate($this->name);
        }

        return $this->description;
    }


}
