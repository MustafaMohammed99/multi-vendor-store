<x-alert-errors />
<div class="form-group">
    <x-form.input label="{{ __('Name') }}" class="form-control-lg" name="name" :value="$admin->name" />
</div>
<div class="form-group">
    <x-form.input label="{{ __('UserName') }}" class="form-control-lg" name="username" :value="$admin->username" />
</div>
<div class="form-group">
    <x-form.input label="{{ __('Email') }}" type="email" name="email" :value="$admin->email" />
</div>
<div class="form-group">
    <x-form.input label="{{ __('Phone number') }}" type="phone_number" name="phone_number" :value="$admin->phone_number" />
</div>
<!-- Password -->
<div class="mt-4">
    <x-form.input label="{{ __('Password') }}" type="password" name="password" />
</div>
<!-- Confirm Password -->
<div class="mt-4">
    <x-form.input label="{{ __('Password confirmation') }}" type="password" name="password_confirmation" />
</div>

<fieldset>
    <legend>{{ __('Roles') }}</legend>

    @foreach ($roles as $role)
        <div class="form-check  @error('roles') is-invalid @enderror">

            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}"
                @checked(in_array($role->id, old('roles', $admin_roles ?? [])))>

            <label class="form-check-label">
                {{ $role->name }}
            </label>
        </div>
    @endforeach
    @error('roles')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</fieldset>

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ $button_label ?? __('Save') }}</button>
</div>
