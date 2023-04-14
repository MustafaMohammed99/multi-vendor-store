@extends('layouts.dashboard')

@section('title', __('Edit Product'))

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">{{__('Products')}}</li>
    <li class="breadcrumb-item active">{{__('Edit Product')}}</li>
@endsection

@section('content')

    <form action="{{ route('dashboard.products.update', $product->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')

        @include('dashboard.products._form', [
            'button_label' => __('Update'),
        ])
    </form>
@endsection



@push('scripts')
    <script>
        const singleElement = document.querySelector('input[id="image"]');
        const multipleElement = document.querySelector('input[id="product_images"]');
        FilePond.registerPlugin(FilePondPluginImagePreview);

        const singlePond = FilePond.create(singleElement, {
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

        const multiplePond = FilePond.create(multipleElement, {
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



    </script>
@endpush
