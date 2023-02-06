<div class="otm-sidebar d-block p-0 pt-3 col-12 col-md-3 col-xl-2 p-0">
    <div class="d-flex flex-column flex-shrink-0 h-100">
        <div class="nav flex-column mb-auto">
            @foreach(\App\View\Components\Admin\SidebarLink::getSidebarLinks() as $sidebarLink)
                {{ $sidebarLink->render() }}
            @endforeach
            @if(Auth::user() !== null && Auth::user()->getHighestRoleLevel() === 999)
                {{ \App\View\Components\Admin\SidebarLink::getLogsURL()->render() }}
            @endif
        </div>
        <div class="nav-item">
            <a class="nav-link sidebar-toggle" href="javascript:toggleSidebar()">
                <i class="sidebar-arrow icon-arrow-right" />
            </a>
        </div>
    </div>
</div>
@push('footer-stack')
    <script type="text/javascript">
        function toggleSidebar() {
            let sidebar = $(".otm-sidebar");
            let arrow = $(".sidebar-arrow");
            if (sidebar.hasClass('shown')) {
                sidebar.removeClass('shown');
                arrow.removeClass('icon-arrow-left');
                arrow.addClass('icon-arrow-right');
            } else {
                sidebar.addClass('shown');
                arrow.addClass('icon-arrow-left');
                arrow.removeClass('icon-arrow-right');
            }
        }
    </script>
@endpush
