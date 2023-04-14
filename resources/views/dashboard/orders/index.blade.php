@extends('layouts.dashboard')

@section('title', __('orders'))

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">{{ __('orders') }}</li>
@endsection

@section('content')


    <x-alert type="success" />
    <x-alert type="info" />

    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
        <select name="payment_status" class="form-control mx-2">
            <option value="">{{ __('All') }}</option>
            <option value="pending" @selected(request('payment_status') == 'pending')>{{ __('Pending') }}</option>
            <option value="paid" @selected(request('payment_status') == 'paid')>{{ __('Paid') }}</option>
            <option value="failed" @selected(request('payment_status') == 'failed')>{{ __('Failed') }}</option>
        </select>
        <button class="btn btn-dark mx-2">{{ __('Filter') }}</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('User') }}</th>
                <th>{{ __('Store') }}</th>
                <th>{{ __('Count proudcts') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Payment status') }}</th>
                <th>{{ __('Created At') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td><a href="{{ route('dashboard.orders.show', $order->id) }}">{{ $order->id }}</a></td>
                    <td>{{ $order->user->name ?? __('guest') }}</td>
                    <td>{{ $order->store->name }}</td>
                    <td>{{ $order->items_count }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->payment_status }}</td>
                    <td>{{ $order->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">{{ __('No orders defined.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $orders->withQueryString()->appends(['search' => 1])->links() }}

@endsection
