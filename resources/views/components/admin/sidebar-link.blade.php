@if($permitted)
    @php $active = isset($search) && str_contains(Request::path(), $search) @endphp
    <div class="nav-item">
        <a href="{{ $url }}" class="nav-link @if($active) active @endif" title="{{ $name }}">
            <span style="color: {{ $active ? '#A3CAEE' : '#295F92' }};">{!! $icon !!}</span>
            <span class="sidebar-hide" style="margin-left: 0.5rem;">{{ $name }}</span>
            @if($active)
                <span class="sidebar-hide selected"></span>
            @endif
        </a>
    </div>
@endif
