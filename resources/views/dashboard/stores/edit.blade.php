@extends('layouts.dashboard')

@section('title', 'Edit Store')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Store</li>
    <li class="breadcrumb-item active">Edit Store</li>
@endsection

@section('content')

    <form action="{{ route('dashboard.stores.update', $store->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')

        @include('dashboard.stores._form', [
            'button_label' => 'Update',
        ])
    </form>

@endsection
