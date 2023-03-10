@extends('layouts.dashboard')

@section('title', 'Stores')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Stores</li>
@endsection

@section('content')

    <div class="mb-5">
        {{-- @if (Auth::user()->can('stores.create')) --}}
        {{-- <a href="{{ route('dashboard.stores.create') }}" class="btn btn-sm btn-outline-primary mr-2">Create</a> --}}
        {{-- @endif --}}
        <a href="{{ route('dashboard.stores.trash') }}" class="btn btn-sm btn-outline-dark">Trash</a>
    </div>

    <x-alert type="success" />
    <x-alert type="info" />

    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
        <x-form.input name="name" placeholder="Name" class="mx-2" :value="request('name')" />
        <select name="status" class="form-control mx-2">
            <option value="">All</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="inactive" @selected(request('status') == 'inactive')>InActive</option>
        </select>
        <button class="btn btn-dark mx-2">Filter</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Products #</th>
                <th>Status</th>
                <th>Created At</th>
                <th>operation</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stores as $store)
                <tr>
                    <td><img src="{{ $store->image_url }}" alt="" height="50"></td>
                    <td>{{ $store->id }}</td>
                    <td><a href="{{ route('dashboard.stores.show', $store->id) }}">{{ $store->name }}</a></td>
                    <td>{{ $store->products_count }}</td>
                    <td>{{ $store->status }}</td>
                    <td>{{ $store->created_at }}</td>
                    <td>
                        {{-- @can('stores.delete') --}}
                        <form action="{{ route('dashboard.stores.destroy', $store->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                        {{-- @endcan --}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No stores defined.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $stores->withQueryString()->appends(['search' => 1])->links() }}

@endsection
