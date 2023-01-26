<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class Wishlist extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'cookie_id', 'user_id', 'product_id',
    ];


    protected static function booted()
    {
        static::addGlobalScope('cookie_id', function (Builder $builder) {
            $builder->where('cookie_id', '=', Wishlist::getCookieId());
        });

        static::creating(function (Wishlist $wishlis) {
            $wishlis->id = Str::uuid();
            $wishlis->cookie_id = Wishlist::getCookieId();
        });
    }

    public static function getCookieId()
    {
        $cookie_id = Cookie::get('wishlis_id');
        if (!$cookie_id) {
            $cookie_id = Str::uuid();
            Cookie::queue('wishlis_id', $cookie_id, 30 * 24 * 60);
        }
        return $cookie_id;
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Anonymous',
        ]);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public static function rules()
    {
        return [
            'product_id' => ['required', 'int', 'exists:products,id'],
        ];
    }
}
