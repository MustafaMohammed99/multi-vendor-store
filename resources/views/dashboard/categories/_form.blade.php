<x-alert-errors />

<div class="form-group">
    <x-form.input label="{{ __('Category Name') }}" class="form-control-lg" role="input" name="name"
        :value="$category->name" />
</div>
<div class="form-group">
    <label for="">{{ __('Category Parent') }}</label>
    <select name="parent_id" class="form-control form-select">
        <option value="">{{ __('Primary Category') }}</option>
        @foreach ($parents as $parent)
            <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="">{{ __('Description') }}</label>
    <x-form.textarea name="description" :value="$category->description" />
</div>


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
@endpush

<x-form.label id="image">{{ __('Image') }}</x-form.label> <!-- Add the translation key for the label text here -->
<div class="form-group" id="filepond_single_image"
    style="{{ ($button_label ?? false) === 'Update' ? (!$category->image_path ? '' : 'display: none') : '' }}">
    <x-form.input type="file" name="image" accept="image/*" id="image" />
</div>

@if ($category->image_path)
    <div id="existing-images">
        <div class="image-box">
            <img src="{{ $category->image_url }}" alt="Image">
            <button type="button" class="cancel-btn" data-model="category" data-id="{{ $category->id }}"
                data-type="single" data-image="{{ $category->image_path }}">{{ __('delete') }}</button>
            <!-- Add the translation key for the button text here -->
        </div>
    </div>
@endif



<div class="form-group">
    <label for="">{{ __('Status') }}</label>
    <div>
        <x-form.radio name="status" :checked="$category->status" :options="['active' => __('Active'), 'archived' => __('Archived')]" />
        <!-- Add the translation keys for the radio button labels here -->
    </div>
</div>
<div class="form-group">
    <button type="submit" id="btn-save" class="btn btn-primary">{{ $button_label ?? __('Save') }}</button>

</div>


@push('scripts')
    <script src="{{ asset('js/filepond.js') }}"></script>
@endpush
