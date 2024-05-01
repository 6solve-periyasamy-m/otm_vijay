<div class="otm-sidebar d-block p-0 pt-3 col-12 col-md-3 col-xl-2 p-0">
    <div class="d-flex flex-column flex-shrink-0 h-100">
        <div class="nav-item mb-2">
            <a class="nav-link sidebar-toggle" href="javascript:toggleSidebar()">
                <img src="images/logokpt.png" alt="Company Name" class="iconLogo hide" height="40px" width="100px" />
                <!-- <x-icon icon="fa-regular fa-circle" class="sidebar-arrow" /> -->
                <x-icon icon="fa-solid fa-bars" class="sidebar-arrow" />
            </a>
        </div>
        <div class="nav flex-column mb-auto overflow-y-auto flex-nowrap">
            @foreach(\App\View\Components\Admin\SidebarLink::getSidebarLinks() as $sidebarLink)
                {{ $sidebarLink->render() }}
            @endforeach
            @if(is_otm())
                {{ \App\View\Components\Admin\SidebarLink::getLogsURL()->render() }}
            @endif
        </div>      
    </div>
</div>
@push('footer-stack')
    <script type="text/javascript">
        function toggleSidebar() {
            let sidebar = $(".otm-sidebar");
            let arrow = $(".sidebar-arrow");
            let icon = $(".iconLogo");
            if (sidebar.hasClass('shown')) {
                sidebar.removeClass('shown');
                arrow.removeClass('fa-solid fa-xmark');
                arrow.addClass('fa-solid fa-bars');
                icon.removeClass('showLogo');
            } else {
                sidebar.addClass('shown');
                arrow.addClass('fa-solid fa-xmark');
                arrow.removeClass('fa-solid fa-bars');
                icon.addClass('showLogo');
            }
        }
    </script>
@endpush
