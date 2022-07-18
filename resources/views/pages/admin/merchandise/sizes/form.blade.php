@extends('layout.form', ['action' => isset($size) ? route('merchandise.size.update', ['size' => $size,]) : route('merchandise.size.store')])

@php /** @var \App\Models\Merchandise\MerchandiseSize|null $size */ $size = $size ?? null @endphp

@section('title', (isset($type) ? 'Update' : 'Create') . ' Merchandise Size')

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $size?->name, 'width' => 11])
    @include('partials.fields.submit', ['width' => 1])
@endsection
