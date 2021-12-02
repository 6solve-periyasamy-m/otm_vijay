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
            <div class="p-3">
                <img class="setting-logo" src="{{ asset(\App\Repository\SettingsRepository::getOrDefault('company.logo', '')) }}">
            </div>
            <div class="p-3 pe-5 d-flex flex-column">
                <span>{{ \App\Repository\SettingsRepository::getOrDefault('company.name', '') }}</span>
                <span><i class="icon-envelope"></i> {{ \App\Repository\SettingsRepository::getOrDefault('company.contact.email', '') }}</span>
                <span><i class="icon-call-end"></i> {{ \App\Repository\SettingsRepository::getOrDefault('company.contact.phone', '') }}</span>                
            </div>
        </div>        
    </nav>
</header>
