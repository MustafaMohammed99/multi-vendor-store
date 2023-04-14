<x-alert-errors />

<div class="form-group">
    <x-form.input label="{{ __('Product Name') }}" class="form-control-lg" role="input" name="name" :value="$product->name" />
</div>

<div class="form-group">
    <x-form.select id="category_id" name="category_id" label="{{ __('Category') }}:" :selected="$product->category_id" :options="$categories" />
</div>


<div class="form-group">
    <label for="">{{ __('Description') }}</label>
    <x-form.textarea name="description" :value="$product->description" />
</div>


<x-form.label id="image">{{ __('Image Original') }}</x-form.label>

<div class="form-group" id="filepond_single_image"
    style="{{ ($button_label ?? false) === 'Update' ? (!$product->image_path ? '' : 'display: none') : '' }}">
    <x-form.input type="file" name="image" accept="image/*" id="image" />
</div>

@if ($product->image_path)
    <div id="existing-images">
        <div class="image-box">
            <img src="{{ $product->image_url }}" alt="{{ __('Image') }}">
            <button type="button" class="cancel-btn" data-model="product" data-id="{{ $product->id }}"
                data-type="single" data-image="{{ $product->image_path }}">{{ __('Delete') }}</button>
        </div>
    </div>
@endif


<div class="mt-4">
    <div class="form-group">
        <x-form.label id="product_images">{{__('Secondary Images product')}}</x-form.label>
        <x-form.input type="file" name="product_images[]" accept="image/*" multiple id="product_images" />
    </div>
</div>

<div id="existing-images">
    @if (is_array($product_images))
        @foreach ($product_images as $image_path => $image_url)
            @if ($image_path)
                <div class="image-box">
                    <img src="{{ $image_url }}" alt="{{__('Image')}}">
                    <button type="button" class="cancel-btn" data-model="product" data-id="{{ $product->id }}"
                        data-type="multiple" data-image="{{ $image_path }}">{{__('Delete')}}</button>
                </div>
            @endif
        @endforeach
    @endif
</div>

<div class="form-group">
    <x-form.input label="{{__('Price')}}" name="price" :value="$product->price" />
</div>

<div class="form-group">
    <x-form.input label="{{__('Compare Price')}}" name="compare_price" :value="$product->compare_price" />
</div>

<div class="form-group">
    <x-form.input label="{{__('Tags')}}" name="tags" :value="$tags" />
</div>

<div class="form-group">
    <label for="">{{__('Status')}}</label>
    <div>
        <x-form.radio name="status" :checked="$product->status" :options="['active' => __('Active'), 'draft' => __('Draft'), 'archived' => __('Archived')]" />
    </div>
</div>

<div class="form-group">
    <button type="submit" id="btn-save" class="btn btn-primary">{{ $button_label ?? __('Save') }}</button>
</div>>


@push('styles')
    <style>
        .filepond--root {
            height: 100%;
        }
    </style>
    <style>
        img {
            width: 200px;
            height: 200px;
        }

        .image-box {
            position: relative;
            display: inline-block;
            margin: 10px;
            border: 1px solid #ccc;
            padding: 5px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .image-box img {
            max-width: 100%;
            height: auto;
        }

        .cancel-btn {
            position: absolute;
            top: 0;
            right: 0;
            margin: 5px;
            padding: 5px;
            background-color: #f00;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
    <link href="{{ asset('css/tagify.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
    <script src="{{ asset('js/tagify.min.js') }}"></script>
    <script src="{{ asset('js/tagify.polyfills.min.js') }}"></script>
    <script>
        var inputElm = document.querySelector('[name=tags]'),
            tagify = new Tagify(inputElm);
    </script>

    <script src="{{ asset('js/filepond.js') }}"></script>
@endpush
