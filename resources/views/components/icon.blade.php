@php
    $attributes = $attributes ?? new \Illuminate\View\ComponentAttributeBag();
    $icon = $icon ?? $attributes?->get('icon', 'redo') ?? 'redo';
    $base = $base ?? 'fas';
@endphp
<i {{ $attributes?->class("{$base} fa-{$icon}") }}>{{$slot??""}}</i>
