@extends('layouts.dashboard')

@section('title', __('Users'))

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">{{ __('Users') }}</li>
@endsection

@section('content')

<div class="mb-5">
    <a href="{{ route('dashboard.users.create') }}" class="btn btn-sm btn-outline-primary mr-2">{{ __('Create') }}</a>
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
        @forelse($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td><a href="{{ route('dashboard.users.show', $user->id) }}">{{ $user->name }}</a></td>
            <td>{{ $user->email }}</td>
            <td></td>
            <td>{{ $user->created_at }}</td>
            <td>
                @can('users.update')
                <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn btn-sm btn-outline-success">{{ __('Edit') }}</a>
                @endcan
            </td>
            <td>
                @can('users.delete')
                <form action="{{ route('dashboard.users.destroy', $user->id) }}" method="post">
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
            <td colspan="6">{{ __('No users defined.') }}</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $users->withQueryString()->links() }}

@endsection
