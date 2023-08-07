<div class="form-group col-12 col-xl-{{ $attributes->get('width', 12) }}" style="padding-left: 5px;">
    <label>{{ $attributes->get('label', "") ?? $slot }} @if($attributes->has('required')) <x-admin.required /> @endif</label>
    <div class="input-group">
        @if($attributes->has('prepend'))
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $attributes->get('prepend') }}</span>
            </div>
        @endif
        <input {{ $attributes->class(['form-control'])->except(['width', 'label', 'prepend', 'append']) }} />
        @if($attributes->has('append'))
            <div class="input-group-append">
                <span class="input-group-text">{{ $attributes->get('append') }}</span>
            </div>
        @endif
    </div>
</div>
