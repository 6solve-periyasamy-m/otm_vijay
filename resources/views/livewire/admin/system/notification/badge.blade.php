@can('read', \App\Models\System\Notification::class)
    @if($unseen !== null && $unseen > 0)
        <span class="badge badge-pill badge-danger">{{ $unseen > 99 ? "99+" : $unseen }}</span>
    @else
        <span></span>
    @endif
@endcan
