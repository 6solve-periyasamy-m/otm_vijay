@php
/**
 * @var \App\Models\System\Brand $branding
 */
$branding = $branding ?? \App\Models\System\Brand::getSystemBrand();
@endphp
<header class="topbar">
    <nav class="navbar">
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
    </nav>
</header>

@push('footer-stack')
    <script type="text/javascript">
        function logout() {
            $.post('{{ route('customer.logout') }}', {'_token': '{{ csrf_token() }}',}).then(function () { window.location = '{{ route('customer.login') }}'; });
        }
    </script>
@endpush
