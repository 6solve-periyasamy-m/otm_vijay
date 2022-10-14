<div class="col-12 col-md-3 col-xl-2 p-0  otm-sidebar d-block py-3">
    <ul class="nav flex-column mb-auto">
        @foreach(\App\View\Components\Admin\SidebarLink::getSidebarLinks() as $sidebarLink)
            {{ $sidebarLink->render() }}
        @endforeach
    </ul>
</div>
