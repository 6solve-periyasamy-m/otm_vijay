@extends('layout.form', ['action' => isset($type) ? route('merchandise.type.update', ['type' => $type,]) : route('merchandise.type.store')])

@php /** @var \App\Models\Merchandise\MerchandiseType|null $type */ $type = $type ?? null @endphp

@section('title', (isset($type) ? 'Update' : 'Create') . ' Merchandise Type')

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $type?->name, 'width' => 11])
    @include('partials.fields.submit', ['width' => 1])
@endsection
