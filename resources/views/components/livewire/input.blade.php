@php
    if($attributes->has('disabled') && $attributes->get('disabled', true)){
        $disabled = true;
        $classes = ['input-disabled'];
    }
    $fieldName = $attributes->get('name');
    $hasError = $errors->has($fieldName);
@endphp
<div class="form-group col-12 col-xl-{{ $attributes->get('width', 12) }}" style="padding-left: 5px;">
    @if($attributes->get('label') !== null)
    <label class="{{ $hasError ? 'text-danger' : '' }}">
        {{ $attributes->get('label', "") ?? $slot }} @if($attributes->has('required')) <x-admin.required /> @endif
        @error($attributes->get('wire:model', $attributes->get('name'))) <span class="text-danger">({{ $message }})</span> @enderror
    </label>
    @endif
    <div class="input-group">
        @if($attributes->has('prepend'))
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $attributes->get('prepend') }}</span>
            </div>
        @endif
        <input {{ ($disabled ?? false) ? 'disabled' : '' }} wire:change="inputChanged('{{$attributes->get("wire:model", $attributes->get("key", null))}}')" {{ $attributes->class(['form-control', ...($classes ?? [])])->except(['width', 'label', 'prepend', 'append','disabled']) }} />
        @if($attributes->has('append'))
            <div class="input-group-append">
                <span class="input-group-text">{{ $attributes->get('append') }}</span>
            </div>
        @endif
    </div>
</div>
