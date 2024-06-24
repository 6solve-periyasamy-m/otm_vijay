@extends('layout.master')

@section('title', 'Notification Center')

@section('content')
    <x-admin.section.card>
        <livewire:admin.system.notification.table />
    </x-admin.section.card>
@endsection
