@extends('layouts.dashboard')

@section('title', __('Admins'))

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        {{ __('Admins') }}</li>
@endsection

@section('content')

    <div class="mb-5">
        @can('create', 'App\Models\Admin')
            <a href="{{ route('dashboard.admins.create') }}" class="btn btn-sm btn-outline-primary mr-2">{{ __('Create') }}</a>
        @endcan
    </div>

    <x-alert type="success" />
    <x-alert type="info" />

    <table class="table">
        <thead>
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Roles') }}</th>
                <th>{{ __('Created At') }}</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($admins as $admin)
                <tr>
                    <td>{{ $admin->id }}</td>
                    <td><a href="{{ route('dashboard.admins.show', $admin->id) }}">{{ $admin->name }}</a></td>
                    <td>{{ $admin->email }}</td>
                    <td></td>
                    <td>{{ $admin->created_at }}</td>
                    <td>
                        @can('update', $admin)
                            <a href="{{ route('dashboard.admins.edit', $admin->id) }}"
                                class="btn btn-sm btn-outline-success">{{ __('Edit') }}</a>
                        @endcan
                    </td>
                    <td>
                        @can('delete', $admin)
                            <form action="{{ route('dashboard.admins.destroy', $admin->id) }}" method="post">
                                @csrf
                                <!-- Form Method Spoofing -->
                                <input type="hidden" name="_method" value="delete">
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">{{ __('No admins defined.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $admins->withQueryString()->links() }}

@endsection
