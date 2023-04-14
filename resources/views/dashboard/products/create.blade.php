@extends('layouts.dashboard')

@section('title', __('Create Products'))


@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">{{ __('Products') }}</li>
@endsection

@section('content')
    <x-alert-errors />

    <form action="{{ route('dashboard.products.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('dashboard.products._form')
    </form>
@endsection

@push('scripts')
    <script>
        const singleElement = document.querySelector('input[id="image"]');
        const multipleElement = document.querySelector('input[id="product_images"]');
        FilePond.registerPlugin(FilePondPluginImagePreview);

        FilePond.create(singleElement, {
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
                        formData.append('name', 'image'); // name image in form
                        formData.append('name_folder', 'products'); //
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


        FilePond.create(multipleElement, {
            server: {
                process: {
                    url: '/filepond/upload',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    onload: (response) => {
                        return response
                    },
                    onerror: (response) => {
                        // Handle error
                    },
                    ondata: (formData) => {
                        formData.append('name', 'product_images'); // name image in form
                        formData.append('name_folder', 'products'); //
                        formData.append('type', 'multiple'); // type image in form single or multiple

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
            },
            allowMultiple: true,
        });

        /*
                // first paramet element second is options filepond
                pond_image = FilePond.create(singleElment, {
                    // acceptedFileTypes: ['image/png'],
                    server: {
                        process: '/filepond/upload',
                        revert: '/filepond/revert',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                    },
                });

                // allowRevert: true // allow revert functionality


                // Initialize the multiple image input
                FilePond.create(multibleElement, {
                    server: {
                        process: '/filepond/upload',
                        revert: '/filepond/revert',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    },
                    allowMultiple: true,
                });

                */
    </script>
@endpush
