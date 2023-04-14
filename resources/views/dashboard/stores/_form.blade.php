@if ($errors->any())
    <div class="alert alert-danger">
        <h3>Error Occured!</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <x-form.input label="Store Name" class="form-control-lg" type="input" name="name" :value="$store->name" />
</div>

<div class="form-group">
    <label for="">Description</label>
    <x-form.textarea name="description" :value="$store->description" />
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

<x-form.label id="image">Image</x-form.label>
<div class="form-group" id="filepond_single_image"
    style="{{ ($button_label ?? false) === 'Update' ? (!$store->logo_image_path ? '' : 'display: none') : '' }}">
    <x-form.input type="file" name="logo_image" accept="image/*" id="logo_image" />
</div>

@if ($store->logo_image_path)
    <div id="existing-images">
        <div class="image-box">
            <img src="{{ $store->logo_image_url }}" alt="Image">
            <button type="button" class="cancel-btn" data-model="store" data-id="{{ $store->id }}"
                data-type="single" data-image="{{ $store->logo_image_path }}">delete</button>
        </div>
    </div>
@endif



<x-form.label id="image">Cover Image</x-form.label>
<div class="form-group" id="filepond_single_image"
    style="{{ ($button_label ?? false) === 'Update' ? (!$store->cover_image_path ? '' : 'display: none') : '' }}">
    <x-form.input type="file" name="cover_image" accept="image/*" id="cover_image" />
</div>

@if ($store->cover_image_path)
    <div id="existing-images">
        <div class="image-box">
            <img src="{{ $store->cover_image_url }}" alt="Image">
            <button type="button" class="cancel-btn" data-model="store" data-id="{{ $store->id }}"
                data-type="single" data-image="{{ $store->cover_image_path }}">delete</button>
        </div>
    </div>
@endif


<div class="form-group">
    <label for="">Status</label>
    <x-form.radio name="status" :checked="$store->status" :options="['active' => 'Active', 'inactive' => 'InActive']" />
</div>



<x-auth-validation-errors />

<div class="form-group">
    <x-form.input label="name owner store " class="form-control-lg" name="user_name" :value="$user->name" />
</div>

<div class="form-group">
    <x-form.input id="phone_parent" name="user_phone_parent" label="phone number owner" type="number" :value="$user->phone_parent"
        class="form-control-border" />
</div>

<div class="form-group">
    <x-form.input label="email owner" type="email" name="user_email" :value="$user->email" />
</div>

{{-- <div class="form-group">
    <label for="">Type user</label>
    <div>
        <x-form.radio name="user_type" :checked="$user->type" :options="['super-admin' => 'super-admin', 'admin' => 'admin', 'user' => 'user']" />
    </div>
</div> --}}

@if ($showPassword === true)
    <div class="form-group">
        <x-form.input label="password owner" type="password" name="user_password" />
    </div>
@endif




<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ $button_label ?? 'Save' }}</button>
</div>



@push('scripts')
    <script src="{{ asset('js/filepond.js') }}"></script>
@endpush
