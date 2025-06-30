<div class="overview_top_bar">
    <p class="overview_title">{{ $title }}</p>
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