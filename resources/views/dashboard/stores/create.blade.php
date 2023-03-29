@extends('layouts.dashboard')

@section('title', 'Store')

@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Store</li>
@endsection

@section('content')

<form action="{{ route('dashboard.stores.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    @include('dashboard.stores._form')
</form>

@endsection
