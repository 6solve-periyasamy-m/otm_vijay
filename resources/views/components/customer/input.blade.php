<div class="form-floating form-group col-12 col-xl-{{ $attributes->get('width', 12) }}">
    <input {{ $attributes->except(['width'])->merge(['type' => 'text', 'class' => 'form-control', 'placeholder' => 'placeholder']) }} >
    <label>{{ $slot }}</label>
</div>
