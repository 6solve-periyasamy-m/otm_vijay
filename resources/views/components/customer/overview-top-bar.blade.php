<div class="overview_top_bar">
    <p class="overview_title">{{ $title }}</p>
    <p>User : {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</p>
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