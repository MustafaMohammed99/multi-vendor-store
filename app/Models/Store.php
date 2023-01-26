<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class Store extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $appends = [
        'image_url',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'store_id', 'id');
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['name'] ?? false, function($builder, $value) {
            $builder->where('stores.name', 'LIKE', "%{$value}%");
        });

        $builder->when($filters['status'] ?? false, function($builder, $value) {
            $builder->where('stores.status', '=', $value);
        });

    }


    public function getImageUrlAttribute()
    {
        if (!$this->logo_image) {
            return 'https://www.incathlab.com/images/products/default_product.png';
        }
        if (Str::startsWith($this->logo_image, ['http://', 'https://'])) {
            return $this->logo_image;
        }
        return asset('storage/' . $this->logo_image);
    }

    public static function rules($id = 0)
    {
        return [
            'name' => [
                'required', 'string', 'min:3', 'max:255',
                // "unique:categories,name,$id",
                Rule::unique('categories', 'name')->ignore($id),
            ],
            'image' => [
                'nullable', 'image', 'max:1048576', 'dimensions:min_width=100,min_height=100',
            ],
            'status' => 'required|in:active,inactive',
        ];
    }
}
