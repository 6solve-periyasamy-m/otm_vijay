<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="p-3 ps-5 d-flex">
                <img class="stamp-logo"
                     src="{{ asset(setting('atol.stamp', '')) }}">
                <div class="d-flex flex-column ms-3">
                    @if(!empty(setting('social.facebook')))
                        <span>
                        <a href="{{ setting('social.facebook') }}"
                           class="link link-primary">
                            Facebook
                        </a>
                    </span>
                    @endif
                    @if(!empty(setting('social.twitter')))
                        <span>
                        <a href="{{ setting('social.twitter') }}"
                           class="link link-primary">
                            Twitter
                        </a>
                    </span>
                    @endif
                    @if(!empty(setting('social.instagram')))
                        <span>
                        <a href="{{ setting('social.instagram') }}"
                           class="link link-primary">
                            Instagram
                        </a>
                    </span>
                    @endif
                </div>
            </div>
            <div class="p-3 dp-down">
                <a href="{{ route('customer.portal') }}">
                    <img class="setting-logo dp-button"
                         src="{{ asset(setting('company.logo', '')) }}">
                </a>
                <div class="dp-content">
                    @if(\App\Repository\Authentication\CustomerAuthenticationRepository::getCustomer() !== null)
                        <a href="{{ route('customer.portal') }}"><i class="icon-home"></i>&nbsp;Home</a>
                        <a href="{{ route('customer.edit') }}"><i class="icon-user"></i>&nbsp;Edit Details</a>
                        <a href="{{ route('customer.finances') }}"><i class="icon-credit-card"></i>&nbsp;Finances</a>
                        <a href="{{ route('customer.itinerary') }}"><i class="icon-globe"></i>&nbsp;Itinerary</a>
                        <a href="{{ route('customer.extras') }}"><i class="icon-diamond"></i>&nbsp;Tour Extras</a>
                        <a href="#" onclick="event.preventDefault();logout();"><i
                                    class="icon-login"></i>&nbsp;Logout</a>
                    @else
                        <a href="{{ route('customer.login') }}"><i class="icon-login"></i>&nbsp;Login</a>
                    @endif
                </div>
            </div>
            <div class="p-3 pe-5 d-flex flex-column">
                <span>{{ setting('company.name', '') }}</span>
                <span><i class="icon-envelope"></i> {{ setting('company.contact.email', '') }}</span>
                <span><i class="icon-call-end"></i> {{ setting('company.contact.phone', '') }}</span>
            </div>
        </div>
    </nav>
</header>

@push('footer-stack')
    <script type="text/javascript">
        function logout() {
            $.post('{{ route('customer.logout') }}', {'_token': '{{ csrf_token() }}',}).then(function () {
                window.location = '{{ route('customer.login') }}';
            });
        }
    </script>
@endpush
