<div class="form-group">
    <x-form.input label="{{ __('Role Name') }}" class="form-control-lg" role="input" name="name" :value="$role->name" />
</div>

<fieldset>
    <legend>{{ __('Abilities') }}</legend>

    @foreach (app('abilities') as $ability_code => $ability_name)
    <div class="row mb-2">
        <div class="col-md-6">
            {{ is_callable($ability_name)? $ability_name() : __($ability_name) }}
        </div>
        <div class="col-md-2">
            <input type="radio" name="abilities[{{ $ability_code }}]" value="allow" @checked(($role_abilities[$ability_code] ?? '') == 'allow')>
            {{ __('Allow') }}
        </div>
        <div class="col-md-2">
            <input type="radio" name="abilities[{{ $ability_code }}]" value="deny" @checked(($role_abilities[$ability_code] ?? '') == 'deny')>
            {{ __('Deny') }}
        </div>
        <div class="col-md-2">
            <input type="radio" name="abilities[{{ $ability_code }}]" value="inherit" @checked(($role_abilities[$ability_code] ?? '') == 'inherit')>
            {{ __('Inherit') }}
        </div>
    </div>
    @endforeach
</fieldset>

<div class="form-group">
    <button type="submit" id="btn-save" class="btn btn-primary">{{ $button_label ?? __('Save') }}</button>
</div>  
