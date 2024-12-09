<div class="alert alert-{{ $color }} alert-dismissible fade show" role="alert">
    {{ $content }}
    <button class="justify-end" onclick="$(this).parent().remove()" type="button" class="close" data-dismiss="alert" aria-label="Close">
        {{ Icon::close() }}
    </button>
</div>
