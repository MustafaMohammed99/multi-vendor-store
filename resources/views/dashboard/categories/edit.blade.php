@extends('layouts.dashboard')

@section('title', __('Edit Category'))

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">{{ __('Categories') }}</li>
    <li class="breadcrumb-item active">{{ __('Edit Category') }}</li>
@endsection

@section('content')

    <form action="{{ route('dashboard.categories.update', $category->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')

        @include('dashboard.categories._form', [
            'button_label' => __('Update')
        ])
    </form>

@endsection




@push('scripts')
    <script>
        const singleElement = document.querySelector('input[id="image"]');
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
                        formData.append('name_folder', 'categories'); //
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
