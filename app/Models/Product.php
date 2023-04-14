<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Stichoza\GoogleTranslate\GoogleTranslate;


class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'category_id', 'store_id',
        'price', 'compare_price', 'status',
    ];

    protected $casts = [
        'image' => 'json',
    ];


    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    protected $appends = [
        'image_url',
        'image_path',
        'name_translate',
        'description_translate',
    ];

    protected static function booted()
    {
        static::addGlobalScope('store', new StoreScope());

        static::creating(function (Product $product) {
            $product->slug = Str::slug($product->name);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,     // Related Model
            'product_tag',  // Pivot table name
            'product_id',   // FK in pivot table for the current model
            'tag_id',       // FK in pivot table for the related model
            'id',           // PK current model
            'id'            // PK related model
        );
    }

    public function images()
    {
        return $this->hasMany(ProductImages::class, 'product_id', 'id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'product_id', 'id');
    }



    public function scopeActive(Builder $builder)
    {
        $builder->where('status', '=', 'active');
    }


    // Accessors
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

    public function getSalePercentAttribute()
    {
        if (!$this->compare_price) {
            return 0;
        }
        return round(100 - (100 * $this->price / $this->compare_price), 1);
    }


    public function scopeFilterBack(Builder $builder, $filters)
    {
        $builder->when($filters['name'] ?? false, function ($builder, $value) {
            $builder->where('products.name', 'LIKE', "%{$value}%");
        });

        $builder->when($filters['status'] ?? false, function ($builder, $value) {
            $builder->where('products.status', '=', $value);
        });
    }

    public function scopeGetPriceRanges(Builder $builder, $multiplier)
    {

        $builder->selectRaw('
    CASE
        WHEN price < ' . (50 * $multiplier) . ' THEN "1 - ' . (50 * $multiplier) . '"
        WHEN price >= ' . (50 * $multiplier) . ' AND price < ' . (100 * $multiplier) . ' THEN "' . (50 * $multiplier) . ' - ' . (100 * $multiplier) . '"
        WHEN price >= ' . (100 * $multiplier) . ' AND price < ' . (500 * $multiplier) . ' THEN "' . (100 * $multiplier) . ' - ' . (500 * $multiplier) . '"
        WHEN price >= ' . (500 * $multiplier) . ' AND price < ' . (1000 * $multiplier) . ' THEN "' . (500 * $multiplier) . ' - ' . (1000 * $multiplier) . '"
        WHEN price >= ' . (1000 * $multiplier) . ' AND price < ' . (5000 * $multiplier) . ' THEN "' . (1000 * $multiplier) . ' - ' . (5000 * $multiplier) . '"
        WHEN price >= ' . (5000 * $multiplier) . ' THEN "' . (5000 * $multiplier) . ' - ∞"
    END AS price_range,
    COUNT(*) AS products_count')
            ->groupBy('price_range')
            ->orderByRaw('
        CASE
            WHEN price_range = "' . (50 * $multiplier) . ' - ' . (100 * $multiplier) . '" THEN 1
            WHEN price_range = "' . (100 * $multiplier) . ' - ' . (500 * $multiplier) . '" THEN 2
            WHEN price_range = "' . (500 * $multiplier) . ' - ' . (1000 * $multiplier) . '" THEN 3
            WHEN price_range = "' . (1000 * $multiplier) . ' - ' . (5000 * $multiplier) . '" THEN 4
            WHEN price_range = "' . (5000 * $multiplier) . ' - ∞" THEN 5
            ELSE 6
        END')
            ->get();
    }


    public function scopeFilteredProducts(Builder $query, $categories_selected, $price_ranges_selected, $search, $sorting)
    {
        if (!empty($categories_selected)) {
            $query->whereIn('category_id', $categories_selected);
        }

        if (!empty($price_ranges_selected)) {
            $price_ranges_handel = array_map(function ($range) {
                [$lower, $upper] = explode(' - ', $range);
                $upper = ($upper === '∞') ? INF : (int)$upper;
                return [(int)$lower, $upper];
            }, $price_ranges_selected);

            $query->where(function ($query) use ($price_ranges_handel) {
                foreach ($price_ranges_handel as $range) {
                    if ($range[1] == 'INF') {
                        $query->orWhere('price', '>=', $range[0]);
                    } else {
                        $query->orWhereBetween('price', $range);
                    }
                }
            });
        }

        if (!empty($search)) {

            $query->when($search ?? false, function ($builder, $value) {
                $builder->where('products.name', 'LIKE', "%{$value}%");
            });
            // $query->when($search ?? false, function ($query, $value) {
            //     $query->where('name', 'LIKE', "%{$value}%")
            //         ->orWhereHas('categories', function ($query) use ($value) {
            //             $query->where('name', 'LIKE', "%{$value}%");
            //         });
            // });
        }


        if (!empty($sorting)) {
            switch ($sorting) {
                case 'a_z':
                    $query->orderBy('name', 'asc');
                    break;
                case 'z_a':
                    $query->orderBy('name', 'desc');
                    break;
                case 'high_low':
                    $query->orderBy('price', 'desc');
                    break;
                case 'low_high':
                    $query->orderBy('price', 'asc');
                    break;
            }
        }

        return $query;
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
            return  $translate->setSource('en')->setTarget('ar')->translate(
                $this->description
            );
        }

        return $this->description;
    }



    public static function rules($id = 0)
    {
        return [
            'category_id' => ['required', 'int', 'exists:categories,id'],
            'name' =>  ['required', 'string', 'min:3', 'max:255',],
            'price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'compare_price' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],

            // 'image' => [
            //     'string', 'required',
            //     // 'image', 'required', 'max:1048576', 'dimensions:min_width=100,min_height=100',
            // ],
            'status' => 'required|in:active,draft,archived',


            // 'admin' => [Rule::exists('admins', 'id')->where(function ($query) use ($id) {
            //     if ($id == 0) //  if == 0 اذا كان الاي ديه صفر يعني الان هوا قاعد بعدل ما بينشأ
            //         return false; // منعتوا من الانشاء لان المنتج بيحتوي على اي ده متجر وهوا  ادمن ما فش معوا متاجر
            // })]
        ];
    }
}
