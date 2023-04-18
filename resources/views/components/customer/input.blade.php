<div class="form-floating form-group col-12 col-xl-{{ $attributes->get('width') ?? $width ?? 12 }}">
    <input {{ $attributes->except('width')->merge(['class' => "form-control float-padding {$attributes->get('name')}-input", 'id' => $attributes->get('name'), 'placeholder' => 'placeholder',]) }} {{ $disable ? 'disabled' : '' }}/>
    <label for="{{ $attributes->get('id') }}">{{ $slot }} @if($attributes->has('required')) <span class="fw-bold">*</span> @endif</label>
    @error($attributes->get('wire:model') ?? $attributes->get('name'))<span class="text-danger">{{ $message }}</span>@enderror
</div>
