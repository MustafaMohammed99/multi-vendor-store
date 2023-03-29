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

<div class="form-group">
    <label for="">logo Image</label>
    <x-form.input type="file" name="logo_image" accept="image/*" />
    @if ($store->image)
        <div>{{ $store->image }}</div>
        <img src="{{ asset('storage/' . $store->image) }}" alt="" height="60">
    @endif
</div>

<div class="form-group">
    <label for="">logo Image</label>
    <x-form.input type="file" name="cover_image" accept="image/*" />
    @if ($store->image)
        <div>{{ $store->image }}</div>
        <img src="{{ asset('storage/' . $store->image) }}" alt="" height="60">
    @endif
</div>


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
