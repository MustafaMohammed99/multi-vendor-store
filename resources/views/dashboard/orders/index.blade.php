@extends('layouts.dashboard')

@section('title', 'orders')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">orders</li>
@endsection

@section('content')


    <x-alert type="success" />
    <x-alert type="info" />

    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
        <select name="payment_status" class="form-control mx-2">
            <option value="">All</option>
            <option value="pending" @selected(request('payment_status') == 'pending')>Pending</option>
            <option value="paid" @selected(request('payment_status') == 'paid')>Paid</option>
            <option value="failed" @selected(request('payment_status') == 'failed')>Failed</option>
        </select>
        <button class="btn btn-dark mx-2">Filter</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User </th>
                <th>Store </th>
                <th>Count proudcts </th>
                <th>Status</th>
                <th>Payment status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td><a href="{{ route('dashboard.orders.show', $order->id) }}">{{ $order->id }}</a></td>
                    <td>{{ $order->user->name ?? 'guest' }}</td>
                    <td>{{ $order->store->name }}</td>
                    <td>{{ $order->items_count }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->payment_status }}</td>
                    <td>{{ $order->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No orders defined.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $orders->withQueryString()->appends(['search' => 1])->links() }}

@endsection
