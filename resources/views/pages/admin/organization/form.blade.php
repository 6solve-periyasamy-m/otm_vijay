@php
/**
 * @var \App\Models\Customer\Organization|null $organization
 */
$organization = $organization ?? null;
@endphp

@extends('layout.form', ['action' => empty($organization) ? route('organizations.store') : route('organizations.update', ['organization' => $organization])])

@section('title', empty($organization) ? 'Create Organization': 'Update Organization')

@section('form-body')
<x-admin.input name="name" width="4" value="{{ $organization?->name }}">Organization Name</x-admin.input>
<x-admin.input name="phone" width="4" value="{{ $organization?->name }}">Contact Telephone</x-admin.input>
<x-admin.input name="email" width="4" value="{{ $organization?->name }}">Contact Email</x-admin.input>
<x-admin.input.text-area name="internal_notes" width="6" value="{{ $organization?->internal_notes }}">Internal Notes</x-admin.input.text-area>
<x-admin.input.text-area name="external_notes" width="6" value="{{ $organization?->external_notes }}">External Notes</x-admin.input.text-area>
<input type="submit" class="btn btn-primary text-white" name="Submit">
@endsection
