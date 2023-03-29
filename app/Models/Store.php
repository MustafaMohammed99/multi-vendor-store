<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class Store extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $appends = [
        'logo_image_url',
        'cover_image_url',
    ];

    protected $fillable = [
        'name', 'slug', 'description', 'logo_image', 'cover_image', 'status',
    ];

    protected $hidden = [
        'cover_image', 'logo_image',
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'store_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'store_id', 'id');
    }

    protected static function booted()
    {
        static::creating(function (Store $store) {
            $store->slug = Str::slug($store->name);
            Validator::make($store->toArray(), [
                'slug' => [
                    'required', 'string', 'min:3', 'max:255',
                    Rule::unique('stores', 'slug')->ignore($store->id),
                ],
            ], ['slug' => 'لا يمكن تكرار العنوان'])->validate();
        });
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['name'] ?? false, function ($builder, $value) {
            $builder->where('stores.name', 'LIKE', "%{$value}%");
        });

        $builder->when($filters['status'] ?? false, function ($builder, $value) {
            $builder->where('stores.status', '=', $value);
        });
    }

    public function getLogoImageUrlAttribute()
    {
        if (!$this->logo_image) {
            return 'https://www.incathlab.com/images/products/default_product.png';
        }
        if (Str::startsWith($this->logo_image, ['http://', 'https://'])) {
            return $this->logo_image;
        }
        return asset('uploads/' . $this->logo_image);
    }

    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) {
            return 'https://www.incathlab.com/images/products/default_product.png';
        }
        if (Str::startsWith($this->cover_image, ['http://', 'https://'])) {
            return $this->cover_image;
        }
        return asset('uploads/' . $this->cover_image);
    }

    public static function rules($id = 0)
    {
        return [
            'name' => [
                'required', 'string', 'min:3', 'max:255',
                Rule::unique('stores', 'name')->ignore($id),
            ],
            'description' => [
                'nullable', 'string', 'min:3',
            ],
            'logo_image' => [
                'nullable', 'image', 'max:1048576', 'dimensions:min_width=100,min_height=100',
            ],
            'cover_image' => [
                'nullable', 'image', 'max:1048576', 'dimensions:min_width=100,min_height=100',
            ],
            'status' => 'required|in:active,inactive',
        ];
    }
}
