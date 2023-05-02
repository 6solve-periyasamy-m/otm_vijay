@php/** @var \App\Models\Accommodation\Accommodation $accommodation */@endphp
<div class="otm-callout">
    <div class="row">
        @if(isset($accommodation->image_url))
            <div class="col-2">
                <img src="{{ asset($accommodation->image_url) }}" class="img-thumbnail image large">
            </div>
        @endif
        <div class="col-{{ isset($accommodation->image_url) ? 10 : 12 }} row">
            <div class="col-12">
                <h4 class="fw-bold">{{ $accommodation->name }}</h4>
            </div>
            <div class="col-12 col-xl-6">
                <p>Audit Date</p>
                <h6 class="fw-bold">{{ f_date($accommodation->audit_date) }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Address</p>
                <h6 class="fw-bold">{{ $accommodation->address }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Description</p>
                <h6 class="fw-bold">{{ $accommodation->description }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Currency</p>
                <h6 class="fw-bold">{{ $accommodation->currency }}</h6>
            </div>
            <div class="col-12">
                <p>Notes</p>
                <h6 class="fw-bold">{{ $accommodation->notes }}</h6>
            </div>
            <div class="col-12">
                @can('update', \App\Models\Accommodation\Accommodation::class)
                    <a class="btn btn-success" href="{{route('accommodations.edit', ['accommodation' => $accommodation,])}}">
                        {{ Icon::edit() }}
                        <span>Edit Accommodation</span>
                    </a>
                @endcan
                <a class="btn btn-secondary" href="{{route('accommodations.rooming', ['accommodation' => $accommodation,])}}">
                    {{ Icon::list() }}
                    <span>View Rooming List</span>
                </a>
                <a class="btn btn-secondary" href="{{route('accommodations.rooming', ['accommodation' => $accommodation, 'notes' => false,])}}">
                    {{ Icon::list() }}
                    <span>View Rooming List (No Notes)</span>
                </a>
            </div>
        </div>
    </div>
</div>
