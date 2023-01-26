@extends('layouts.dashboard')

@section('title', $store->name)

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Store</li>
    <li class="breadcrumb-item active">{{ $store->name }}</li>
@endsection

@section('content')

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>Proudct</th>
                <th>Category</th>
                <th>Price</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>

            @forelse($products as $product)
                <tr>
                    <td><img src="{{ $product->image_url }}" alt="" height="50"></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ??'' }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->status }}</td>
                    <td>{{ $product->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No products defined.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $products->links() }}

@endsection
