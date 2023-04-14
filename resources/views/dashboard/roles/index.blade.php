@extends('layouts.dashboard')

@section('title', __('Roles'))

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">{{ __('Roles') }}</li>
@endsection

@section('content')

    <div class="mb-5">
        @can('create', 'App\Models\Role')
            <a href="{{ route('dashboard.roles.create') }}" class="btn btn-sm btn-outline-primary mr-2">{{ __('Create') }}</a>
        @endcan
    </div>

    <x-alert type="success" />
    <x-alert type="info" />

    <table class="table">
        <thead>
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Created At') }}</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td><a href="{{ route('dashboard.roles.show', $role->id) }}">{{ $role->name }}</a></td>
                    <td>{{ $role->created_at }}</td>
                    <td>
                        @can('update', $role)
                            <a href="{{ route('dashboard.roles.edit', $role->id) }}"
                                class="btn btn-sm btn-outline-success">{{ __('Edit') }}</a>
                        @endcan
                    </td>
                    <td>
                        @can('delete', $role)
                            <form action="{{ route('dashboard.roles.destroy', $role->id) }}" method="post">
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
                    <td colspan="4">{{ __('No roles defined.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $roles->withQueryString()->links() }}

@endsection
