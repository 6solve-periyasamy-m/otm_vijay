<div class="overview_top_bar">
    <p class="overview_title">
         @if(isset($back))
            <span><a href="{{ $backUrl }}"><img src="/images/customer/images/arrow-left.svg" alt="arrow left"></a></span>
        @endif
        {{ $title }} @if(isset($tourStatus)) <span class="status-btn status-btn-{{ $tourStatusColor }}"> {{ $tourStatus }} </span> @endif
    </p>
    {{-- <p>User : {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</p> --}}
    @if(!isset($search) || $search)
    <div class="search_field">
        <p>
            <form method="GET">
                <input type="text" name="search" placeholder="Search and enter" value="{{ request('search') }}">
            </form>
        </p>
    </div>
    @endif
</div>