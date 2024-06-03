@php $template = $template ?? null; @endphp
@extends('layout.master')

@section('title', 'Default Text Templates')

@section('content')
    <livewire:admin.system.large-text-template.form :template="$template" />
@endsection