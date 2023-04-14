@props(['id' => '', 'name', 'checked' => '', 'options' => []])

{{-- لسا ما جربتوش --}}
{{-- @foreach ($options as $option)
    <div class="form-check  @error($name) is-invalid @enderror">

        <input class="form-check-input" type="checkbox" name="{{ $name }}" value="{{ $option->id }}"
            @checked(in_array($option->id, old($name, $checked ?? [])))>

        <label class="form-check-label">
            {{ $option->name }}
        </label>
    </div>
@endforeach --}}

@error($name)
    <p class="invalid-feedback">{{ $message }}</p>
@enderror
