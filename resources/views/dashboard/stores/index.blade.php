@extends('layouts.dashboard')

@section('title', __('Stores'))

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">{{ __('Stores') }}</li>
@endsection

@section('content')
    <div class="mb-5">
        @can('create', 'App\Models\Store')
            <a href="{{ route('dashboard.stores.create') }}" class="btn btn-sm btn-outline-primary mr-2">{{ __('Create') }}</a>
        @endcan
        @can('create', 'App\Models\Store')
            <a href="{{ route('dashboard.stores.trash') }}" class="btn btn-sm btn-outline-dark">{{ __('Trash') }}</a>
        @endcan
    </div>

    <x-alert type="success" />
    <x-alert type="info" />

    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
        <x-form.input name="name" placeholder="Name" class="mx-2" :value="request('name')" />
        <select name="status" class="form-control mx-2">
            <option value="">All</option>
            <option value="active" @selected(request('status') == 'active')>{{ __('Active') }}</option>
            <option value="inactive" @selected(request('status') == 'inactive')>{{ __('InActive') }}</option>
        </select>
        <button class="btn btn-dark mx-2">{{ __('Filter') }}</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>{{ __('logo') }}</th>
                <th>{{ __('cover') }}</th>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Products #') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Created At') }}</th>
                <th>{{ __('operation') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stores as $store)
                <tr>
                    <td><img src="{{ $store->logo_image_url }}" alt="" height="50"></td>
                    <td><img src="{{ $store->cover_image_url }}" alt="" height="50"></td>
                    <td>{{ $store->id }}</td>
                    <td><a href="{{ route('dashboard.stores.show', $store->id) }}">{{ $store->name }}</a></td>
                    <td>{{ $store->products_count }}</td>
                    <td>{{ $store->status }}</td>
                    <td>{{ $store->created_at }}</td>
                    <td>
                        @can('delete', $store)
                            <form action="{{ route('dashboard.stores.destroy', $store->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">{{ __('No stores defined') }}.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $stores->withQueryString()->appends(['search' => 1])->links() }}
@endsection
