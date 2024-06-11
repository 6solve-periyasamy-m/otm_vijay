<div class="navbar navbar-expand text-light sticky-top otm-navbar px-md-3 flex-column flex-md-row">
    <div class="navbar-brand abs">
        <a class="text-light fw-bold" href="#">
            <img src="{{ asset('images/logo.png') }}" class="logo inline">
            <span>Octopus Travel Matrix</span>
        </a>
    </div> 
    <div class="navbar-nav ms-auto">
        <div class="nav-item sidebar-toggler">
            <a class="nav-item nav-link" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" class="bi" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"></path>
                </svg>
            </a>
        </div>
        @if(Auth::check())
            <div class="nav-item dp-down">
                <a class="nav-item nav-link dp-button" href="#">
                    <span class='text-light'>
                        <livewire:admin.system.notification.badge />
                        {{ Auth::user()->name }}
                    </span>
                    <img src="{{ asset(Auth::user()->avatar_url) }}" class="img-thumbnail inline">
                </a>
                <div class="dp-content">
                    <a href="{{ route('notifications.all') }}">{{ Icon::note() }}&nbsp;Notifications</a>
                    <a href="{{ route('users.profile') }}">{{ Icon::view() }}&nbsp;View Profile</a>
                    <a href="#" onclick="event.preventDefault();logout();">{{ Icon::logout() }}&nbsp;Logout</a>
                </div>
            </div>
        @else
            <div class="nav-item">
                <a class="nav-item nav-link" href="{{ route('login') }}">
                    <span class='text-light'>Login</span>
                </a>
            </div>
        @endif
    </div>
</div>

@push('footer-stack')
    <script type="text/javascript">
        function logout() {
            $.post('{{ route('logout') }}', {'_token': '{{ csrf_token() }}',}).always(function () { window.location = '{{ route('homepage') }}'; });
        }
    </script>
@endpush
