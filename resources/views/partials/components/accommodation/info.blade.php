<div class="otm-callout">
    <h3 class="fw-bold">{{ $accommodation->name }}</h3>
    <p>{{ $accommodation->audit_date }}</p>
    <p>{{ $accommodation->address }}</p>
    <p>{{ $accommodation->description }}</p><br>    
    <a class="btn btn-success" href="{{route('accommodations.edit', ['accommodation' => $accommodation,])}}">
        <i class="icon-note"></i>
        <span>Edit Accommodation</span>
    </a>
</div>
