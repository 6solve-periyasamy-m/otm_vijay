<div class="form-group col-12 {{ 'col-xl-' . $attributes->get('width', 12) }}">
    @if($attributes->get('label') !== null)
        <label>{{ $attributes->get('label', "") ?? $slot }} @if($attributes->has('required')) <x-admin.required /> @endif</label>
    @endif
    <select class="form-select" {{ $attributes->except(['width', 'label',]) }}>
        @foreach($items as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
</div>