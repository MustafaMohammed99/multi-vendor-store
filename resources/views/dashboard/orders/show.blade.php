@extends('layouts.dashboard')

@section('title', 'order items')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a  href="{{ route('dashboard.orders.index') }}">Orders</a>
    </li>
    <li class="breadcrumb-item active">Order Items</li>

@endsection

@section('content')

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>Proudct</th>
                <th>Category</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>

            @forelse($order->products as $product)
                <tr>
                    <td><img src="{{ $product->image_url }}" alt="" height="50"></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '' }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->order_item->quantity }}</td>
                    <td>{{ $product->order_item->quantity * $product->price }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No products defined.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- {{ $order->products->links() }} --}}

@endsection
