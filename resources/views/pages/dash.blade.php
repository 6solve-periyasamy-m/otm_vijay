@extends('layout.master')

@section('title', 'Dashboard')

@section('content')
    <div class="card">
        <div class="card-body">
            <img src="{{ asset(\App\Repository\SettingsRepository::getOrDefault('company.logo', 'images/octlogo.png')) }}" alt="Dummy Data">
        </div>
    </div>
@endsection
