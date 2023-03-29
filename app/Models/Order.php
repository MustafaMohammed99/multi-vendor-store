<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'user_id', 'payment_method', 'status', 'payment_status',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Guest'
        ]);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items', 'order_id', 'product_id', 'id', 'id')
            ->using(OrderItem::class)
            ->as('order_item')
            ->withPivot([
                'quantity', 'options',
            ]);
    }

    // public function recipients()
    // {
    //     return $this->hasManyThrough(
    //         Recipient::class,
    //         Message::class,
    //         'conversation_id',
    //         'message_id',
    //         'id',
    //         'id'
    //     );
    // }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function addresses()
    {
        return $this->hasMany(OrderAddress::class);
    }

    public function billingAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'id')
            ->where('type', '=', 'billing');

        //return $this->addresses()->where('type', '=', 'billing');
    }

    public function shippingAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'id')
            ->where('type', '=', 'shipping');
    }

    // public function delivery()
    // {
    // return $this->hasOne(Delivery::class);
    // }

    protected static function booted()
    {
        static::creating(function (Order $order) {
            // 20220001, 20220002
            $order->number = Order::getNextOrderNumber();
        });
    }

    public static function getNextOrderNumber()
    {
        // SELECT MAX(number) FROM orders
        $year =  Carbon::now()->year;
        $number = Order::whereYear('created_at', $year)->max('number');
        if ($number) {
            return $number + 1;
        }
        return $year . '0001';
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['payment_status'] ?? false, function ($builder, $value) {
            $builder->where('orders.payment_status', '=', $value);
        });
    }

    // 'store_id', 'user_id', 'payment_method', 'status', 'payment_status',

    public static function rules()
    {
        return [
            'store_id' => ['required', 'int', 'exists:stores,id'],
            'user_id' => ['required', 'int', 'exists:users,id'],
            'status' => 'required|in:pending,processing,delivering,completed,cancelled,refunded',
            'payment_method' => ['required', 'string'],
            'payment_status' => 'required|in:pending,paid,failed',
        ];
    }
}
