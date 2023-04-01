@extends('layouts.dashboard')

@section('title', 'Products')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Products</li>
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
        const singleElment = document.querySelector('input[id="image"]');
        const multibleElement = document.querySelector('input[id="product_images"]');
        FilePond.registerPlugin(FilePondPluginImagePreview);

        // first paramet element second is options filepond
        pond_image = FilePond.create(singleElment, {
            server: {
                process: '/upload/filepond',
                revert: '/revert/filepond',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                /*
                     // validation: {
                     //     allowedFileTypes: ['image/gif'],
                     //     // allowedFileTypes: ['image/png', 'image/jpeg', 'image/gif'],
                     //     // display validation errors
                     //     server: {
                     //         url: '/filepond/validate',
                     //         process: {
                     //             method: 'POST',
                     //             headers: {
                     //                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
                     //             },
                     //             onload: (response) => {
                     //                 if (response.status === 422) {
                     //                     const errors = response.body.errors.filepond;
                     //                     for (const error of errors) {
                     //                         alert(error);
                     //                     }
                     //                     throw new Error('Validation error');
                     //                 }
                     //             }
                     //         }
                     //     }
                     // }
                 */
            },
            // allowRevert: true // allow revert functionality
        });

        // pond_image.addPlugin(FilePondPluginImagePreview);


        // Initialize the multiple image input
        FilePond.create(multibleElement, {
            server: {
                process: '/upload/filepond',
                revert: '/revert/filepond',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            },
            allowMultiple: true,
            // allowRevert: true // allow revert functionality
        });

        FilePond.create(document.querySelector('input[type="file"]'), {
            server: {
                process: '/filepond/process',
                revert: '/filepond/revert',
                fetch: '/filepond/fetch',
                restore: '/filepond/restore',
                load: '/filepond/load',
                // add validation callback
                validation: {
                    allowedFileTypes: ['image/png', 'image/jpeg', 'image/gif'],
                    // display validation errors
                    server: {
                        url: '/filepond/validate',
                        process: {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            onload: (response) => {
                                if (response.status === 422) {
                                    const errors = response.body.errors.filepond;
                                    for (const error of errors) {
                                        alert(error);
                                    }
                                    throw new Error('Validation error');
                                }
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
