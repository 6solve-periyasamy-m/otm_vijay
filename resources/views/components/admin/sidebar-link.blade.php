@if($permitted)
    @php $active = isset($search) && str_contains(Request::path(), $search) @endphp
    <div class="nav-item">
        <a href="{{ $url }}" class="nav-link @if($active) active @endif">
            <span style="color: {{ $active ? '#A3CAEE' : '#295F92' }};">{!! $icon !!}</span>
            <span class="sidebar-hide">{{ $name }}</span>
            @if($active)
                <span class="sidebar-hide selected"></span>
            @endif
        </a>
    </div>
@endif
