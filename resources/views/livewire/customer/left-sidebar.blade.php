
<header class="topbar">
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
                  <!-- <li class="tours_menu submenu-toggle">
                      <a href="{{ route('customer.itinerary') }}" class="{{ Route::currentRouteName() == 'customer.itinerary' ? 'active' : '' }}"><img src="/images/customer/images/airplane.svg" alt="tours" />TOURS</a>
                      <ul class="travel_sub_menu">
                        @foreach($upcomingOrders as $order)
                            <li>
                                <a href="{{ url('customer/itinerary/' . $order->booking_reference . '/' . $orderCustomer->customer_id) }}">
                                    {{ $order->tour?->event?->name}}
                                </a>
                            </li>
                        @endforeach
                      </ul>
                  </li> -->
                  <li class="tours_menu submenu-toggle {{ Route::currentRouteName() == 'customer.itinerary' ? 'open' : '' }}">
                        <a href="{{ route('customer.itinerary') }}" class="{{ Route::currentRouteName() == 'customer.itinerary' ? 'active' : '' }}">
                            <img src="/images/customer/images/airplane.svg" alt="tours" />TOURS
                        </a>
                        <ul class="travel_sub_menu" style="{{ Route::currentRouteName() == 'customer.itinerary' ? 'display: block;' : '' }}">
                            @foreach($upcomingOrders as $order)
                                <li>
                                    <a href="{{ url('customer/itinerary/' . $order->booking_reference . '/' . $orderCustomer->customer_id) }}">
                                        {{ $order->tour?->event?->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                  <li>
                      <a href="{{ route('customer.finances') }}" class="{{ Route::currentRouteName() == 'customer.finances' ? 'active' : '' }}"><img src="/images/customer/images/wallet.svg" alt="finances" />FINANCES</a>
                  </li>
                  <li>
                      <a href="{{ route('customer.edit') }}" class="{{ Route::currentRouteName() == 'customer.edit' ? 'active' : '' }}"><img src="/images/customer/images/user.svg" alt="your details" />YOUR DETAILS</a>
                  </li>
                  <li>
                      <a href="{{ route('customer.faq') }}" class="{{ Route::currentRouteName() == 'customer.faq' ? 'active' : '' }}"><img src="/images/customer/images/message-question.svg" alt="faqs" />FAQ's</a>
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
        window.addEventListener('load', function () {
            const targetDiv = document.querySelector('.inner_content');
            const header = document.getElementById('sidebar');
            console.log(targetDiv);
            if (targetDiv && header) {
            const divHeight = targetDiv.offsetHeight;
            header.style.height = divHeight + 'px';
            }
        });

        document.querySelectorAll('.submenu-toggle').forEach(button => {
            button.addEventListener('click', event => {
                const menuItem = button.closest('.tours_menu');
                const submenu = menuItem.querySelector('.travel_sub_menu');
 
                if (submenu) {
                    const isOpen = menuItem.classList.contains('open');
 
                    if (isOpen) {
                        submenu.style.display = 'none';
                        menuItem.classList.remove('open');
                    } else {
                        submenu.style.display = 'block';
                        menuItem.classList.add('open');
                    }
                }
            });
        });
 
        document.querySelectorAll('.travel_sub_menu').forEach(submenu => {
            submenu.addEventListener('click', event => {
                event.stopPropagation();
            });
        });
    </script>
@endpush
