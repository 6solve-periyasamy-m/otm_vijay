<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="p-3 ps-5 d-flex">
                <img class="stamp-logo" src="{{ asset(\App\Repository\SettingsRepository::getOrDefault('atol.stamp', '')) }}">
                <div class="d-flex flex-column ms-3">
                    <span><font-awesome-icon icon="facebook" class="menu-icon" /></span>
                    <span>@twitterID</span>
                    <span>@instagramID</span>
                </div>
            </div>
            <div class="p-3 dp-down">
                <img class="setting-logo dp-button" src="{{ asset(\App\Repository\SettingsRepository::getOrDefault('company.logo', '')) }}" onclick="window.location = '{{ route('customer.portal') }}'">
                <div class="dp-content">
                    <a href="#" onclick="event.preventDefault();logout();"><i class="icon-login"></i>&nbsp;Logout</a>
                </div>
            </div>
            <div class="p-3 pe-5 d-flex flex-column">
                <span>{{ \App\Repository\SettingsRepository::getOrDefault('company.name', '') }}</span>
                <span><i class="icon-envelope"></i> {{ \App\Repository\SettingsRepository::getOrDefault('company.contact.email', '') }}</span>
                <span><i class="icon-call-end"></i> {{ \App\Repository\SettingsRepository::getOrDefault('company.contact.phone', '') }}</span>                
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