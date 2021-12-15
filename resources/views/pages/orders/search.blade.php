@extends('layout.master')

@section('title', 'All Orders')

@section('content')
    <div class="row row justify-content-center">
        <div class="col-6 col-md-5 col-xl-3">
            <img src="{{ asset('images/octlogo.png') }}" class="maxwidth"/>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-end">
            @can('create', \App\Models\Order::class)
            <div class="mb-3">
                <a href="{{ route('orders.create') }}" class="btn btn-primary text-white">
                    <i class="icon-plus"></i>
                    Create Order
                </a>
            </div>
            @endcan
            <div>
                <form action="{{ route("orders.all")}}" method="get">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="query-input" name="query" placeholder="Search Query" value="{{ $query ?? "" }}">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-amber" label="Search">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
    
    <table class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Order Date</th>
            <th scope="col">Lead Booker</th>
            <th scope="col">Booking Reference</th>
            <th scope="col">Tour</th>
            <th scope="col">Order Status</th>
        </tr>
        </thead>
        @foreach($data as $row)
            <tr>
                <td>{{$row->ordered_on}}</td>
                <td>{{$row->lead_booker_first_name . ' ' . $row->lead_booker_last_name }}</td>
                <td><a href="{{ route('orders.view', ['order' => $row->order_id]) }}" class="link-info"><u>{{$row->booking_reference}}</u></a></td>
                <td>{{$row->tour_title}}</td>
                <td><h6 class="order-{{$row->order_id}} badge fw-bold">Order Status</h6></td>
                @push('footer-ready')
                    getOrderStatus({{$row->order_id}});
                @endpush
            </tr>
        @endforeach
    </table>
@endsection

@push('footer-stack')
<script type="text/javascript">
    function getOrderStatus(id) {
        $.ajax({
            url: '{{ route('api.order.status.stub') }}/' + id,
            type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', }
        }).then(function (data) {
            $('.order-' + id).text(data.status).addClass('badge-' + data.color);
        });
    }
</script>
@endpush
