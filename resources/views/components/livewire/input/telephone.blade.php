@php
    if($attributes->has('disabled') && $attributes->get('disabled', true)){
        $disabled = true;
        $classes = ['input-disabled'];
    }
    $fieldName = $attributes->get('name');
    $hasError = $errors->has($fieldName);
    $id = $attributes->get('id', Str::random());
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
        <div wire:ignore>
            <div class="d-flex">
                <input id="{{$id}}" {{ ($disabled ?? false) ? 'disabled' : '' }} wire:change="inputChanged('{{$attributes->get("wire:model", $attributes->get("key", null))}}')" {{ $attributes->class(['form-control', ...($classes ?? [])])->except(['id', 'width', 'label', 'prepend', 'append','disabled']) }} />
            </div>
            <script type="text/javascript">
                window.intlTelInput(document.getElementById("{{$id}}"), {
                    loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.11.2/build/js/utils.js"),
                    separateDialCode: true,
                })
                @isset($_instance)
                jQuery(document.getElementById("{{$id}}")).on('change', function (event) {
                    @this.set('{{ $attributes->get('wire:model', $attributes->get('name')) }}', iti.getNumber());
                });
                @endisset
            </script>
        </div>
        @if($attributes->has('append'))
            <div class="input-group-append">
                <span class="input-group-text">{{ $attributes->get('append') }}</span>
            </div>
        @endif
    </div>
</div>
