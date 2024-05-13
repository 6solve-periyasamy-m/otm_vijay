@php if($attributes->has('disabled')) $classes = ['input-disabled']; @endphp
<div class="form-group col-12 col-xl-{{ $attributes->get('width', 12) }}" style="padding-left: 5px;">
    <label>{{ $attributes->get('label', "") ?? $slot }} @if($attributes->has('required')) <x-admin.required /> @endif</label>
    <div class="input-group">
        <textarea {{ $attributes->class(['form-control', ...($classes ?? [])])->except(['width', 'label', 'prepend', 'append']) }}></textarea>
    </div>
</div>
