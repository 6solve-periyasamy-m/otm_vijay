@extends('layout.master')

@section('title', 'All Tours')

@section('footer-script')
<script type="text/javascript">
    $(document).ready( function () { $('#tour').DataTable({fixedHeader: true}); });
</script>
@endsection

@section('content')
    <div class='card'>
        <div class="card-body">
            <a class="btn btn-success float-end" href="{{ route('tours.create') }}">
                <i class="icon-plus"></i>
                <span>Create New</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class='card-body'>
            <table id="tour" style="width: 100%;" class="table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Event</th>
                    <th scope="col">Category</th>
                    <th scope="col">Description</th>
                    <th scope="col">Date From</th>
                    <th scope="col">Date To</th>
                    <th scope="col">Base Price Per Person</th>
                    <th scope="col">Margin</th>
                    <th scope="col">Deposit</th>
                    <th scope="col">Single Occupancy Surcharge</th>
                    <th scope="col">Stock Control Active</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Booking Form Url</th>
                    <th scope="col">Is Active</th>
                    <th scope="col">Notes</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                @foreach($tours as $tour)
                    @include('partials.models.tours.row', [
                    'tour' => $tour,
                    'event' => isset($tour->event) ? $tour->event->event_title : "None",
                    'name' => $tour->name,
                    'description' => $tour->description,
                    'date_from' => $tour->date_from,
                    'date_to' => $tour->date_to,
                    'base_price_per_person' => $tour->base_price_per_person,
                    'margin' => $tour->margin,
                    'deposit' => $tour->deposit,
                    'single_occupancy_surcharge' => $tour->single_occupancy_surcharge,
                    'stock_control_active' => $tour->stock_control_active,
                    'stock' => $tour->stock,
                    'booking_form_url' => $tour->booking_form_url,
                    'is_active' => $tour->is_active,
                    'notes' => $tour->notes,
                    ])
                @endforeach
            </table>
        </div>
    </div>
@endsection
