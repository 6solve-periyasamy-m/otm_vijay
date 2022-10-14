@if($permitted)
    @php $active = isset($search) && str_contains(Request::path(), $search) @endphp
    <li>
        <a href="{{ $url }}" class="nav-link @if($active) active @endif">
            <i class="icon-{{ $icon }}"></i>
            <span>{{ $name }}</span>
            @if($active)
                <span class="selected"></span>
            @endif
        </a>
    </li>
@endif
