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



    protected $fillable = [
        'name', 'slug', 'description', 'logo_image', 'cover_image', 'status',
    ];

    protected $casts = [
        'logo_image' => 'json',
        'cover_image' => 'json',
    ];

    protected $appends = [
        'logo_image_url',
        'logo_image_path',
        'cover_image_url',
        'cover_image_path',
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

    // Accessors image_url
    public function getLogoImageUrlAttribute()
    {
        // $google_image = is_string($this->image) ? json_decode($this->image) : $this->image;

        $google_image = json_decode($this->logo_image);
        if ($google_image !== null && isset($google_image->url)) {
            return $google_image->url;
        }
        return 'https://www.incathlab.com/images/products/default_product.png';
    }

    public function getLogoImagePathAttribute()
    {
        $google_image = json_decode($this->logo_image);
        if ($google_image !== null && isset($google_image->path)) {
            return $google_image->path;
        }
        return null;
    }

    // Accessors cover_image_url
    public function getCoverImageUrlAttribute()
    {
        // $google_image = is_string($this->image) ? json_decode($this->image) : $this->image;

        $google_image = json_decode($this->cover_image);
        if ($google_image !== null && isset($google_image->url)) {
            return $google_image->url;
        }
        return 'https://www.incathlab.com/images/products/default_product.png';
    }

    public function getCoverImagePathAttribute()
    {
        $google_image = json_decode($this->cover_image);
        if ($google_image !== null && isset($google_image->path)) {
            return $google_image->path;
        }
        return null;
    }
}
