<div class="form-group">
    <x-form.input label="Name" class="form-control-lg" name="name" :value="$admin->name" />
</div>
<div class="form-group">
    <x-form.input label="UserName" class="form-control-lg" name="username" :value="$admin->username" />
</div>
<div class="form-group">
    <x-form.input label="Email" type="email" name="email" :value="$admin->email" />
</div>

<div class="form-group">
    <x-form.input label="Phone number" type="phone_number" name="phone_number" :value="$admin->phone_number" />
</div>

<!-- Password -->
<div class="mt-4">
    <x-input-label for="password" :value="__('Password')" />

    <x-text-input id="password" class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required autocomplete="new-password" />

    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

<!-- Confirm Password -->
<div class="mt-4">
    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                    type="password"
                    name="password_confirmation" required />

    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
</div>

<fieldset>
    <legend>{{ __('Roles') }}</legend>

    @foreach ($roles as $role)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}"
                @checked(in_array($role->id, old('roles', $admin_roles ?? [])))>
            <label class="form-check-label">
                {{ $role->name }}
            </label>
        </div>
    @endforeach
</fieldset>

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ $button_label ?? 'Save' }}</button>
</div>
