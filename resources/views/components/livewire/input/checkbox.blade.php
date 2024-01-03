<div class="form-group col-12 col-xl-{{ $attributes->get('width', 12) }} my-auto" style="padding-left: 5px;">
    <input {{ $attributes->class(['form-check-input'])->merge(['type' => 'checkbox'])->except(['width', 'label']) }}>
    <label class="form-check-label">{{ $attributes->get('label', "") ?? $slot }}</label>
</div>
