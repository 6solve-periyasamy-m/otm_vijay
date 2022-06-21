@extends('layout.customer', ['overflow' => false,])

@section('title', 'Customer Portal')

@section('content')
<div style="position: relative;">
    <div class="d-flex justify-content-center align-items-center h-75 mt-4">
        <div class="donut-menu">
            <div class="menu-item">
            </div>
            <div class="menu-item">
            </div>
            <div class="menu-item">
            </div>
            <div class="menu-item">
            </div>
            <div class="donut-center">
            </div>
        </div>
        <div class="click-menu">
            <div class="menu-item" onclick="window.location = '{{ route('customer.edit') }}'">
                <div class="menu-text">
                    <span class="icon-user menu-icon"></span>
                    <span>Your Details</span>
                </div>
            </div>
            <div class="menu-item" onclick="window.location = '{{ route('customer.finances') }}'">
                <div class="menu-text">
                    <span class="icon-credit-card menu-icon"></span>
                    <span>Your Finance</span>
                </div>
            </div>
            <div class="menu-item" onclick="window.location = '{{ route('customer.extras') }}'">
                <div class="menu-text">
                    <span class="icon-diamond menu-icon"></span>
                    <span>Your Extras</span>
                </div>
            </div>
            <div class="menu-item" onclick="window.location = '{{ route('customer.itinerary') }}'">
                <div class="menu-text">
                    <span class="icon-globe menu-icon"></span>
                    <span>Your Tours</span>
                </div>
            </div>
        </div>
        <div class="customer-portal-mobile">
            <div class="customer-portal-mobile-item" onclick="window.location = '{{ route('customer.edit') }}'">
                <div class="item-wrapper">
                    <span class="icon-user item-icon"></span>
                    <span class="item-text">Your Details</span>
                </div>
            </div>
            <div class="customer-portal-mobile-item" onclick="window.location = '{{ route('customer.finances') }}'">
                <div class="item-wrapper">
                    <span class="icon-credit-card item-icon"></span>
                    <span class="item-text">Your Finance</span>
                </div>
            </div>
            <div class="customer-portal-mobile-item" onclick="window.location = '{{ route('customer.extras') }}'">
                <div class="item-wrapper">
                    <span class="icon-diamond item-icon"></span>
                    <span class="item-text">Your Extras</span>
                </div>
            </div>
            <div class="customer-portal-mobile-item" onclick="window.location = '{{ route('customer.itinerary') }}'">
                <div class="item-wrapper">
                    <span class="icon-globe item-icon"></span>
                    <span class="item-text">Your Tours</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<div class="customer-footer" style="position: fixed; bottom: 0;">
    <hr />
    <div class="socials">
        @if(!empty(\App\Repository\SettingsRepository::get('social.facebook')))
          <div class="facebook">
              <a href="{{ \App\Repository\SettingsRepository::get('social.facebook') }}" class="icon-social-facebook"></a>
          </div>
        @endif
        @if(!empty(\App\Repository\SettingsRepository::get('social.twitter')))
          <div class="twitter">
            <a href="{{ \App\Repository\SettingsRepository::get('social.twitter') }}" class="icon-social-twitter"></a>
          </div>
        @endif
        @if(!empty(\App\Repository\SettingsRepository::get('social.instagram')))
            <div class="instagram">
                <a href="{{ \App\Repository\SettingsRepository::get('social.instagram') }}" class="icon-social-instagram"></a>
            </div>
        @endif
      </div>
    <div class="d-flex justify-content-between align-items-center footer-wrapper" style="font-size: 13px; font-weight: 600;">
        <span class="p-3 pe-5 d-flex flex-column">
            <img class="stamp-logo sidebar-logo" src="{{ asset(\App\Repository\SettingsRepository::getOrDefault('atol.stamp', '')) }}" />
        </span>
        <span class="p-3 d-flex flex-column footer-client-details">
            <span>{{ \App\Repository\SettingsRepository::getOrDefault('company.name', '') }}</span>
            <span><i class="icon-envelope"></i> {{ \App\Repository\SettingsRepository::getOrDefault('company.contact.email', '') }}</span>
            <span><i class="icon-call-end"></i> {{ \App\Repository\SettingsRepository::getOrDefault('company.contact.phone', '') }}</span>
        </span>
    </div>
</div>
@endsection
