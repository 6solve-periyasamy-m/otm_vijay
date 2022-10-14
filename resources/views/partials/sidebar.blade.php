<div class="otm-sidebar d-flex flex-column flex-shrink-0 p-0 pt-3 col-12 col-md-3 col-xl-2 p-0">
    <ul class="nav flex-column mb-auto">
        @foreach(\App\View\Components\Admin\SidebarLink::getSidebarLinks() as $sidebarLink)
            {{ $sidebarLink->render() }}
        @endforeach
    </ul>
</div>
