@php
    /** @var \App\Models\User $user */
    $self = $user->id === auth()->user()->id;
    $editable = ($self || auth()->user()->getHighestRoleLevel() > $user->getHighestRoleLevel());
    $roles = \App\Transforms\PermissionTransforms::getRolesForDropdown(\App\Repository\Authentication\PermissionsRepository::getAvailableRoles())
@endphp

@extends('layout.master')

@section('title', 'View Profile')

@section('content')
    <div class="row">
        <div class="col-md-2">
            <x-admin.section.card>
                @if($editable)
                    <form method="post" action="{{ route('users.avatar', ['user' => $user,]) }}"
                          enctype="multipart/form-data">
                        @endif
                        <div class="mt-4 text-center">
                            <div class="overlay-container">
                                <label for="avatar" class="my-auto">
                                    <img src="{{ $user->avatar_url }}"
                                         class="rounded-circle {{ $editable ? 'hover-upload' : '' }} image med-small"
                                         alt="{{ $user->name }}"/>
                                    @if($editable)
                                        <div class="image-overlay">Upload new picture</div>
                                    @endif
                                </label>
                            </div>
                        </div>
                        @if($editable)
                            @csrf
                            <input type="file" name="avatar" id="avatar" style="display: none;"
                                   onchange="form.submit()">
                    </form>
                @endif
            </x-admin.section.card>
        </div>
        <div class="col-md-10">
            <x-admin.section.card>
                <form method="post" action="{{ route('users.update', ['user' => $user,]) }}" class="row">
                    @csrf
                    <input class="d-none" name="user_id" value="{{ $user->id }}"/>
                    <x-livewire.input disabled="{{!$editable}}" name="name" value="{{ $user->name }}" label="Name"
                                      width="6"/>
                    @if ($editable && !$self)
                        @include('partials.fields.dropdown', ['name' => 'Role', 'field' => 'role', 'values' => $roles, 'selected' => $user->getCurrentRole()?->name, 'width' => 6])
                    @else
                        <x-livewire.input disabled label="Role" value="{{ $user->roles->implode('title', ', ') }}" width="6"/>
                    @endif
                    <x-livewire.input disabled="{{!$editable}}" name="telephone" value="" label="Contact Number"
                                      width="{{ $editable ? 5 : 6 }}"/>
                    <x-livewire.input disabled="{{!$editable}}" name="email" value="{{ $user->email }}" label="Email"
                                      width="{{ $editable ? 5 : 6 }}"/>
                    @if($editable)
                        <div class="col-xl-2 flex justify-center my-auto">
                            <input type="submit" class="btn btn-success" value="Save Details">
                        </div>
                    @endif
                </form>
            </x-admin.section.card>
        </div>
    </div>
    @if($editable && $self)
        <hr class="splitter"/>
        <x-admin.section.card>
            <x-slot:title>Change Password</x-slot:title>
            <form action="{{ route('users.password', ['user' => $user,]) }}" method="post" class="row">
                @csrf
                <x-livewire.input type="password" name="current_password" label="Current Password" width="3"/>
                <x-livewire.input type="password" name="new_password" label="New Password" width="3"/>
                <x-livewire.input type="password" name="new_password_confirmation" label="Confirm New Password"
                                  width="3"/>
                <div class="col-xl-2 flex justify-center my-auto">
                    <input type="submit" class="btn btn-success" value="Change Password">
                </div>
                <div class="col-xl-1"></div>
            </form>
        </x-admin.section.card>
    @endif
@endsection