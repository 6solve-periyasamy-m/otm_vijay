<div class="col-12 col-xl-{{ $attributes->get('width', 12) }} my-auto" style="padding-left: 5px;">
    <div class="pretty p-switch p-fill">
        <input {{ $attributes->merge(['type' => 'checkbox'])->except(['width', 'label']) }}>
        <div class="state p-{{$attributes->get('color', 'primary')}}">
            <label>{{ $attributes->get('label', "") ?? $slot }}</label>
        </div>
        @error($attributes->get('wire:model', $attributes->get('name'))) <span class="text-danger">({{ $message }})</span> @enderror
    </div>
</div>
