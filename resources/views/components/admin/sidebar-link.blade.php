@if($permitted)
    @php $active = isset($search) && str_contains(Request::path(), $search) @endphp
    <div class="nav-item">
        <a href="{{ $url }}" class="nav-link @if($active) active @endif">
            <x-icon icon="{{ $icon }}" />
            <span class="sidebar-hide">{{ $name }}</span>
            @if($active)
                <span class="sidebar-hide selected"></span>
            @endif
        </a>
    </div>
@endif
