@php
/**
 * @var \App\Models\System\Brand $branding
 */
$branding = $branding ?? \App\Models\System\Brand::getSystemBrand();
@endphp
<header class="topbar">
    {{--<nav class="navbar">
      <div class="container-fluid flex-nowrap">
          <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="icon-menu"></span>
          </button>
        <div class="navbar-brand" style="width: 100%; padding-right: 30px;">
          <div class="d-flex justify-content-center">
            <img class="setting-logo" src="{{ $branding->image }}">
          </div>
        </div>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
          <div class="offcanvas-header">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            <div style="width: 100%; display: flex; justify-content: center; align-items: center; ">
                <img class="setting-logo sidebar-logo" style="margin-right: 20px;" src="{{ $branding->image }}">
            </div>
          </div>
          <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1">
                <div>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                    @if(\App\Repository\Authentication\CustomerAuthenticationRepository::getCustomer() !== null)
                      <li class="nav-item">
                        <a href="{{ route('customer.portal') }}" class="nav-link link-dark" aria-current="page">
                          <span class="icon-home"></span>
                          &nbsp;Home
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('customer.edit') }}" class="nav-link link-dark">
                          <span class="icon-user"></span>
                          &nbsp;Your Details
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('customer.finances') }}" class="nav-link link-dark">
                          <span class="icon-credit-card"></span>
                          &nbsp;Your Finances
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('customer.itinerary') }}" class="nav-link link-dark">
                          <span class="icon-globe"></span>
                          &nbsp;Your Tours
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('customer.extras') }}" class="nav-link link-dark">
                          <span class="icon-diamond"></span>
                          &nbsp;Your Extras
                        </a>
                      </li>
                      <li>
                        <a href="#" onclick="event.preventDefault();logout();" class="nav-link link-dark">
                          <span class="icon-login"></span>
                          &nbsp;Logout
                        </a>
                      </li>
                      @else
                        <li>
                          <a href="{{ route('customer.login', ['from' => Request::path(),]) }}" class="nav-link link-dark">
                              <span class="icon-login"></span>
                              &nbsp;Login
                          </a>
                        </li>
                        @endif
                    </ul>
                </div>
                <div style="position: fixed; bottom: 0;">
                    <hr/>
                    <div class="socials">
                        @if(!empty($branding->facebook))
                            <div class="facebook">
                                <a href="{{ $branding->facebook }}" class="icon-social-facebook"></a>
                            </div>
                        @endif
                        @if(!empty($branding->twitter))
                            <div class="twitter">
                                <a href="{{ $branding->twitter }}" class="icon-social-twitter"></a>
                            </div>
                        @endif
                        @if(!empty($branding->instagram))
                            <div class="instagram">
                                <a href="{{ $branding->instagram }}" class="icon-social-instagram"></a>
                            </div>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center"
                         style="font-size: 13px; font-weight: 600;">
                        <span class="p-3 pe-5 d-flex flex-column">
                            <img class="stamp-logo sidebar-logo" src="{{ asset(setting('atol.stamp', '')) }}"/>
                        </span>
                        <span class="p-3 d-flex flex-column">
                            <span>{{ $branding->name }}</span>
                            <span>{{ Icon::email() }} {{ $branding->email }}</span>
                            <span>{{ Icon::phone() }} {{ $branding->phone }}</span>
                        </span>
                    </div>
                </div>
            </ul>
          </div>
        </div>
      </div>
    </nav> --}}

    <nav id="sidebar">
      <div class="sidebar_logo_menu">
          <div class="sidebar-header">
              <img src="/images/customer/images/KPTravel_Logo_Horizontal_RGB_White.svg" alt="kpt logo">
          </div>
              <ul class="list-unstyled menu_list">
                @if(\App\Repository\Authentication\CustomerAuthenticationRepository::getCustomer() !== null)
                  <li>
                      <a href="{{ route('customer.portal') }}" class="{{ Route::currentRouteName() == 'customer.portal' ? 'active' : '' }}"><img src="/images/customer/images/category.svg" alt="category" />OVERVIEW</a>
                  </li>
                  <li>
                      <a href="{{ route('customer.itinerary') }}" class="{{ Route::currentRouteName() == 'customer.itinerary' ? 'active' : '' }}"><img src="/images/customer/images/airplane.svg" alt="tours" />TOURS</a>
                  </li>
                  <li>
                      <a href="{{ route('customer.finances') }}" class="{{ Route::currentRouteName() == 'customer.finances' ? 'active' : '' }}"><img src="/images/customer/images/wallet.svg" alt="finances" />FINANCES</a>
                  </li>
                  <li>
                      <a href="{{ route('customer.edit') }}" class="{{ Route::currentRouteName() == 'customer.edit' ? 'active' : '' }}"><img src="/images/customer/images/user.svg" alt="your details" />YOUR DETAILS</a>
                  </li>
                  <li>
                      <a href="{{ route('customer.extras') }}" class="{{ Route::currentRouteName() == 'customer.extras' ? 'active' : '' }}"><img src="/images/customer/images/message-question.svg" alt="faqs" />FAQS</a>
                  </li>
                  <li>
                      <a href="logout" onclick="event.preventDefault();logout();"><img src="/images/customer/images/login.svg" alt="logout" />LOGOUT</a>
                  </li>
                  @endif
              </ul>
      </div>
      <div class="contact_details">
          <div class="email_id">
              <img src="/images/customer/images/sms.svg" alt="sms icon"><a href="mailto:{{ $branding->email }}"><span>{{ $branding->email }}</span></a>
          </div>
          <div class="phone_no">
              <img src="/images/customer/images/call.svg" alt="call icon"><a href="tel:{{ $branding->phone }}"><span>{{ $branding->phone }}</span></a>
          </div>
          <div class="social_media">
            @if(!empty($branding->facebook))
              <span class="facebook_icon"><a href="{{ $branding->facebook }}"><img src="/images/customer/images/facebook.svg" alt="facebook icon"></a></span>
            @endif
            @if(!empty($branding->twitter))
              <span class="twitter_icon"><a href="{{ $branding->twitter }}"><img src="/images/customer/images/twitter.svg" alt="twitter icon"></a></span>
            @endif  
            @if(!empty($branding->instagram))
              <span class="instagram_icon"><a href="{{ $branding->instagram }}"><img src="/images/customer/images/instagram.svg" alt="instagram icon"></a></span>
            @endif
              <span class="linkedin_icon"><a href=""><img src="/images/customer/images/linkedin.svg" alt="linkedin icon"></a></span>
          </div>
          <p><a href="#">Terms & Conditions</a></p>
      </div> 
  </nav>
  
</header>

@push('footer-stack')
    <script type="text/javascript">
        function logout() {
            $.post('{{ route('customer.logout') }}', {'_token': '{{ csrf_token() }}',}).then(function () { window.location = '{{ route('customer.login') }}'; });
        }
    </script>
@endpush
