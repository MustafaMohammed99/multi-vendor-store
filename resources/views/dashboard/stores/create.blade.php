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

@push('scripts')
    <script>
        const logo_imageElement = document.querySelector('input[id="logo_image"]');
        const cover_imageElement = document.querySelector('input[id="cover_image"]');
        FilePond.registerPlugin(FilePondPluginImagePreview);

        FilePond.create(logo_imageElement, {
            server: {
                process: {
                    url: '/filepond/upload',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    onload: (response) => {
                        //   بتيجي البيانات من كنتروللر الستور الي بيخزن الصورة
                        return response // هادا اللي بيرجع ل ريقرت والي بيرجع للكنتروللر
                    },
                    onerror: (response) => {
                        // Handle error
                    },
                    ondata: (formData) => {
                        formData.append('name', 'logo_image'); // name image in form
                        formData.append('name_folder', 'stores'); //
                        formData.append('type', 'single'); // type image in form single or multiple

                        return formData;
                    }
                },
                // revert: '/filepond/revert',
                revert: {
                    url: '/filepond/revert',
                    method: 'delete',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    onload: (response) => {
                        // Handle response
                    },
                    onerror: (response) => {
                        // Handle error
                    },
                    ondata: null,
                }
            }
        });

        FilePond.create(cover_imageElement, {
            server: {
                process: {
                    url: '/filepond/upload',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    onload: (response) => {
                        //   بتيجي البيانات من كنتروللر الستور الي بيخزن الصورة
                        return response // هادا اللي بيرجع ل ريقرت والي بيرجع للكنتروللر
                    },
                    onerror: (response) => {
                        // Handle error
                    },
                    ondata: (formData) => {
                        formData.append('name', 'cover_image'); // name image in form
                        formData.append('name_folder', 'stores'); //
                        formData.append('type', 'single'); // type image in form single or multiple

                        return formData;
                    }
                },
                // revert: '/filepond/revert',
                revert: {
                    url: '/filepond/revert',
                    method: 'delete',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    onload: (response) => {
                        // Handle response
                    },
                    onerror: (response) => {
                        // Handle error
                    },
                    ondata: null,
                }
            }
        });
    </script>
@endpush
