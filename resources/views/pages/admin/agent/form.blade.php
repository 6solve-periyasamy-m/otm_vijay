@php
/**
 * @var \App\Models\Customer\Agent|null $agent
 */
$agent = $agent ?? null;
@endphp

@extends('layout.form', ['action' => empty($agent) ? route('agents.store') : route('agents.update', ['agent' => $agent])])

@section('title', empty($agent) ? 'Create Agent': 'Update Agent')

@section('form-body')
<x-admin.input name="first_name" width="40" value="{{ $agent?->first_name }}">First Name</x-admin.input>
<x-admin.input name="last_name" width="40" value="{{ $agent?->last_name }}">Last Name</x-admin.input>
<x-admin.input name="email" width="4" value="{{ $organization?->contact_email }}">Contact Email</x-admin.input>
<input type="submit" class="btn btn-primary text-white" name="Submit">
@endsection
