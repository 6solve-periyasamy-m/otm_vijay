@extends('layout.form', ['action' => isset($variant) ? route('$merchandise.variant.update', ['type' => $type,]) : route('merchandise.variant.store')])

@php /** @var \App\Models\Merchandise\Variant|null $variant */ $variant = $variant ?? null @endphp

@section('title', (isset($type) ? 'Update' : 'Create') . ' Variant')

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $variant?->name, 'width' => 11])
    @include('partials.fields.submit', ['width' => 1])
@endsection
