@php
/**
 * @var \App\Models\Customer\Agent|null $agent
 */
$agent = $agent ?? null;
@endphp

@extends('layout.form', ['action' => empty($agent) ? route('agents.store') : route('agents.update', ['agent' => $agent])])

@section('title', empty($agent) ? 'Create Agent': 'Update Agent')

@section('form-body')
<x-livewire.input name="first_name" width="40" value="{{ $agent?->first_name }}">First Name</x-livewire.input>
<x-livewire.input name="last_name" width="40" value="{{ $agent?->last_name }}">Last Name</x-livewire.input>
<x-livewore.input name="email" width="4" value="{{ $organization?->contact_email }}">Contact Email</x-livewire.input>
<input type="submit" class="btn btn-primary text-white" name="Submit">
@endsection
