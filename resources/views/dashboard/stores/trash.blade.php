@extends('layouts.dashboard')

@section('title', 'Trashed Stores')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">Stores</li>
    <li class="breadcrumb-item active">Trash</li>
@endsection

@section('content')

<div class="mb-5">
    <a href="{{ route('dashboard.stores.index') }}" class="btn btn-sm btn-outline-primary">Back</a>
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
            <th>Products#</th>
            <th>Status</th>
            <th>Deleted At</th>
            <th colspan="2"></th>
        </tr>
    </thead>
    <tbody>
        @forelse($stores as $store)
        <tr>
            <td><img src="{{ asset('storage/' . $store->image) }}" alt="" height="50"></td>
            <td>{{ $store->id }}</td>
            <td>{{ $store->name }}</td>
            <td>{{ $store->products_count }}</td>
            <td>{{ $store->status }}</td>
            <td>{{ $store->deleted_at }}</td>
            <td>
                <form action="{{ route('dashboard.stores.restore', $store->id) }}" method="post">
                    @csrf
                    @method('put')
                    <button type="submit" class="btn btn-sm btn-outline-info">Restore</button>
                </form>
            </td>
            <td>
                <form action="{{ route('dashboard.stores.force-delete', $store->id) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">No stores defined.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $stores->withQueryString()->appends(['search' => 1])->links() }}

@endsection
