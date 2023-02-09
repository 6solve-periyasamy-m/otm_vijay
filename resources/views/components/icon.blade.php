@php
    $attributes = $attributes ?? new \Illuminate\View\ComponentAttributeBag();
    $icon = $icon ?? $attributes?->get('icon', 'redo') ?? 'redo';
@endphp
<i {{ $attributes?->class("icon-{$icon}") }}>{{$slot??""}}</i>
