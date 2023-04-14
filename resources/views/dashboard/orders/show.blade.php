@extends('layouts.dashboard')

@section('title', __('Order Items'))

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a  href="{{ route('dashboard.orders.index') }}">{{ __('Orders') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Order Items') }}</li>

@endsection

@section('content')

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>{{ __('Product') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Price') }}</th>
                <th>{{ __('Quantity') }}</th>
                <th>{{ __('Total') }}</th>
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
                    <td colspan="5">{{ __('No products defined.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- {{ $order->products->links() }} --}}

@endsection
