@extends('layouts.dashboard')

@section('title', __('Categories'))

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">{{ __('Categories') }}</li>
@endsection

@section('content')

    <div class="mb-5">
        @if (Auth::user()->can('categories.create'))
            <a href="{{ route('dashboard.categories.create') }}"
                class="btn btn-sm btn-outline-primary mr-2">{{ __('Create') }}</a>
        @endif
        <a href="{{ route('dashboard.categories.trash') }}" class="btn btn-sm btn-outline-dark">{{ __('Trash') }}</a>
    </div>

    <x-alert type="success" />
    <x-alert type="info" />

    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
        <x-form.input name="name" placeholder="{{ __('Name') }}" class="mx-2" :value="request('name')" />
        <select name="status" class="form-control mx-2">
            <option value="">{{ __('All') }}</option>
            <option value="active" @selected(request('status') == 'active')>{{ __('Active') }}</option>
            <option value="archived" @selected(request('status') == 'archived')>{{ __('Archived') }}</option>
        </select>
        <button class="btn btn-dark mx-2">{{ __('Filter') }}</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Parent') }}</th>
                <th>{{ __('Products #') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Created At') }}</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td><img src="{{ $category->image_url }}" alt="" height="50"></td>

                    <td>{{ $category->id }}</td>
                    <td><a href="{{ route('dashboard.categories.show', $category->id) }}">{{ $category->name }}</a></td>
                    <td>{{ $category->parent->name }}</td>
                    <td>{{ $category->products_count }}</td>
                    <td>{{ $category->status }}</td>
                    <td>{{ $category->created_at }}</td>
                    <td>
                        @can('categories.update')
                            <a href="{{ route('dashboard.categories.edit', $category->id) }}"
                                class="btn btn-sm btn-outline-success">{{ __('Edit') }}</a>
                        @endcan
                    </td>
                    <td>
                        @can('categories.delete')
                            <form action="{{ route('dashboard.categories.destroy', $category->id) }}" method="post">
                                @csrf
                                <!-- Form Method Spoofing -->
                                {{-- <input type="hidden" name="_method" value="delete"> --}}
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">{{ __('No categories defined.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $categories->withQueryString()->appends(['search' => 1])->links() }}

@endsection
