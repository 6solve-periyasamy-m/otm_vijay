@php
/** @var \App\Models\User $user */
$self = $user->id === auth()->user()->id;
$editable = ($self || auth()->user()->getHighestRoleLevel() > $user->getHighestRoleLevel());
$roles = \App\Transforms\PermissionTransforms::getRolesForDropdown(\App\Repository\Authentication\PermissionsRepository::getAvailableRoles());
$canForce = is_otm() && !$user->isOtm();
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
                    <x-livewire.input disabled="{{!$editable}}" name="telephone" value="{{ $user->telephone }}" label="Contact Number"
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
        <x-admin.section.accordion id="security">
            <x-slot:title>Account Security</x-slot:title>
            {{-- Change Password Dialog --}}
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
            {{-- Two Factor Auth Dialog --}}
            @if(config('auth.google-2fa.enabled', false))
                @php $secret = $user->otp_secret ?? \Google2FA::generateSecretKey();  @endphp
                <x-admin.section.card>
                    <x-slot:title>Two-Factor Authentication</x-slot:title>
                    @if($user->otp_secret === null)
                    <div class="row">
                        <div class="col-2 flex justify-center my-auto">
                            <img class="image large" src="{{ generate_qr($user->getTwoFactorUrl($secret)) }}" />
                        </div>
                        <div class="col-8">
                            <div>
                                To enable two-factor authentication, follow the steps below.
                                <ol class="list-group list-group-numbered">
                                    <li class="list-group-item">Download a Google 2FA compatible app, such as Google Authenticator
                                        (<a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en_GB&gl=US">Android</a>/
                                        <a href="https://apps.apple.com/us/app/google-authenticator/id388497605">iOS</a>) or Authy
                                        (<a href="https://play.google.com/store/apps/details?id=com.authy.authy">Android</a>/
                                        <a href="https://apps.apple.com/us/app/twilio-authy/id494168017">iOS</a>)
                                    </li>
                                    <li class="list-group-item">Add a new account to the app and scan the QR code on the left</li>
                                    <li class="list-group-item">Enter the 2FA Code shown on the app, and click submit</li>
                                </ol>
                            </div>
                            <form action="{{ route('users.2fa.enable', ['user' => $user]) }}" method="post" class="row">
                                @csrf
                                <input class="d-none" name="otp_secret" value="{{$secret}}">
                                <x-livewire.input width="8" name="otp_code" label="Confirm One-Time Code" />
                                <div class="col-xl-4 my-auto">
                                    <input type="submit" class="btn btn-success" value="Enable Two Factor Authentication" />
                                </div>
                            </form>
                        </div>
                    </div>
                    @else
                        @if(flag('2fa.enforce', false))
                        <div class="fw-bold">
                            Two factor is enabled on your account! Please contact a system administrator if you can no longer access your two-factor to reset.
                        </div>
                        @else
                            <div>
                                <h4 class="fw-bold">Two factor is enabled on your account! You may disable it below if you wish:</h4>
                                <form class="row" method="post" action="{{ route('users.2fa.disable', ['user' => $user,]) }}">
                                    @csrf
                                    <x-livewire.input name="otp_code" width="8" label="Confirm One-Time Code" />
                                    <div class="col-xl-4 my-auto">
                                        <input type="submit" class="btn btn-warning" value="Disable Two Factor Authentication" />
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endif
                </x-admin.section.card>
            @endif
        </x-admin.section.accordion>
    @elseif($canForce && $user->otp_secret !== null)
        <x-admin.section.accordion id="security">
            <x-slot:title>Account Security</x-slot:title>
            <x-admin.section.card>
                <x-slot:title>Two Factor Authentication</x-slot:title>
                <form action="{{ route('users.2fa.disable.force', ['user' => $user,]) }}" method="post" onsubmit="return confirm('Are you sure you wish to disable 2FA on this account?')">
                    <input class="btn btn-danger`" type="submit" value="Force remove 2FA" />
                </form>
            </x-admin.section.card>
        </x-admin.section.accordion>
    @endif
    <x-admin.section.accordion id="consultancy">
        <x-slot:title>Consultation Record</x-slot:title>
        <div class="row">
            <div class="col-md-6">
                <x-admin.section.card>
                    <x-slot:title>Orders</x-slot:title>
                    <table class="datatable table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Reference</th>
                            <th scope="col">Travellers</th>
                            <th scope="col">Cost</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->orders as $order)
                            <tr>
                                <th scope="row"><a href="{{ route('orders.view', ['order' => $order]) }}">{{ $order->booking_reference }}</a></th>
                                <td>{{ $order->orderCustomers()->count() }}</td>
                                <td>{{ f_currency($order->cost) }}</td>
                                <td>{{ $order->status->badge() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </x-admin.section.card>
            </div>
            <div class="col-md-6">
                <x-admin.section.card>
                    <x-slot:title>Quotes</x-slot:title>
                    <table class="datatable table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Reference</th>
                            <th scope="col">Lead Traveller</th>
                            <th scope="col">Expiry</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->quotes as $quote)
                            <tr>
                                <th scope="row"><a href="{{ route('quotes.view', ['quote' => $quote]) }}">{{ $quote->reference }}</a></th>
                                <td>{{ $quote->leadTraveller->name }}</td>
                                <td>{{ f_date($quote->expires) }}</td>
                                <td>{{ $quote->status->badge() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </x-admin.section.card>
            </div>
        </div>
    </x-admin.section.accordion>
    <x-admin.section.accordion id="security-audit">
        <x-slot:title>Security Audit</x-slot:title>
        <x-admin.section.card>
            <x-slot:title>Events</x-slot:title>
            <livewire:admin.user.event-log :user="$user"/>
        </x-admin.section.card>

    </x-admin.section.accordion>
@endsection