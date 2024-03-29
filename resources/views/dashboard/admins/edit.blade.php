@extends('layouts.dashboard')

@section('title', __('Edit Admin'))

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">{{ __('Admins') }}</li>
    <li class="breadcrumb-item active">{{ __('Edit Admin') }}</li>
@endsection

@section('content')

    <form action="{{ route('dashboard.admins.update', $admin->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')

        @include('dashboard.admins._form', [
            'button_label' => __('Update'),
            'is_required' => false
        ])

    </form>

@endsection

