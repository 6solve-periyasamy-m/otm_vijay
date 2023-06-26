<div class="form-group col-12 col-xl-{{ $attributes->get('width', 12) }}" style="padding-left: 5px;">
    <label>{{ $attributes->get('label', "") ?? $slot }} @if($attributes->has('required')) <x-admin.required /> @endif</label>
    <input {{ $attributes->class(['form-control'])->except(['width', 'label']) }}>
</div>
