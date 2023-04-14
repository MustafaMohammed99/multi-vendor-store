<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $request = request();
        $orders = Order::with('user:id,name', 'store:id,name')->withCount('items')
            ->filter($request->query())
            ->paginate(7);

        return view('dashboard.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Show the details of a specific order

        $order = Order::where('id', $order->id)->with(['products' => function ($query) {
            $query->with('category');
        }])->first();

        // $order = Order::where('id', $order->id)->with('products' )->get();
        // dd($order);
        return view('dashboard.orders.show', compact('order'));
    }
}
