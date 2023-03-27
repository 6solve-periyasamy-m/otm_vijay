@php
    $attributes = $attributes ?? new \Illuminate\View\ComponentAttributeBag();
    $icon = $icon ?? $attributes?->get('icon', 'redo') ?? 'redo';
    $base = $base ?? 'fas';
@endphp
<div class="d-inline-flex align-content-center justify-content-center" style="width: 16px; height: 16px; font-size: 16px;">
    <i {{ $attributes?->class("{$base} fa-{$icon}") }}>{{$slot??""}}</i>
</div>
