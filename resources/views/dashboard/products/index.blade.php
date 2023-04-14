@extends('layouts.dashboard')

@section('title', __('Products'))

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">{{ __('Products') }}</li>
@endsection

@section('content')

    <div class="mb-5">
        <a href="{{ route('dashboard.products.create') }}" class="btn btn-sm btn-outline-primary mr-2">{{ __('Create') }}</a>
        <a href="{{ route('dashboard.products.trash') }}" class="btn btn-sm btn-outline-dark">{{ __('Trash') }}</a>
    </div>

    <x-alert type="success" />
    <x-alert type="info" />

    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
        <x-form.input name="name" placeholder="{{ __('Name') }}" class="mx-2" :value="request('name')" />
        <select name="status" class="form-control mx-2">
            <option value="">{{ __('All') }}</option>
            <option value="active" @selected(request('status') == 'active')>{{ __('Active') }}</option>
            <option value="archived" @selected(request('status') == 'archived')>{{ __('Archived') }}</option>
            <option value="draft" @selected(request('status') == 'draft')>{{ __('Draft') }}</option>
        </select>
        <button class="btn btn-dark mx-2">{{ __('Filter') }}</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Store') }}</th>
                <th>{{ __('Price') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Created At') }}</th>
                <th colspan="2">{{ __('Operations') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td><img src="{{ $product->image_url }}" alt="" height="50"></td>

                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name_translate }}</td>
                    <td>{{ $product->category->name_translate ?? '' }}</td>
                    <td>{{ $product->store->name ?? '' }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->status }}</td>
                    <td>{{ $product->created_at }}</td>
                    <td>
                        <a href="{{ route('dashboard.products.edit', $product->id) }}"
                            class="btn btn-sm btn-outline-success">{{ __('Edit') }}</a>
                    </td>
                    <td>
                        <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="post">
                            @csrf
                            <!-- Form Method Spoofing -->
                            <input type="hidden" name="_method" value="delete">
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">{{ __('No products defined.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $products->withQueryString()->appends(['search' => 1])->links() }}

@endsection
