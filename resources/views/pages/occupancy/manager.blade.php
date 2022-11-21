@extends('layout.master')

@section('title', 'Occupancy Manager')

@push('header-stack')
    <!--suppress HtmlUnknownTarget -->
    <link href="{{ asset('css/admin/occupancy.css') }}" type="text/css" rel="stylesheet" />
    <script src="{{ asset('js/admin/functions.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/occupancy.js') }}" type="text/javascript"></script>
@endpush

@section('content')
    <div class="card">
        <div class="card-body col-12">
            <div class="customers section-box drop-shadow col-12"></div>
            <div class="col-12" style="margin: 10px;">
                <select class="room-types">
                    @foreach($rooms as $data)
                        <option value="{{ $data['id'] }}" name="{{ $data['name'] }}" occupancy="{{ $data['size'] }}">{{ $data['name'] }} - Space: {{ $data['size'] }}</option>
                    @endforeach
                </select>
                <a href="#" class="btn btn-danger round" onclick="reset()">Reset</a>
                <a href="#" class="btn btn-warning round" onclick="addRoom()">Add Room</a>
                <a href="#" class="btn btn-success round" onclick="submit()">Save</a>
                <a href="{{ route('orders.view', ['order' => $order,]) }}" class="btn btn-info round">Return to Order</a>
            </div>
            <div class="manager section-box drop-shadow droppable"></div>
        </div>
    </div>
@endsection

{{-- Templates --}}
@push('footer-stack')
<script type="text/template" data-template="bed">
    <div class="bed">${content}</div>
</script>

<script type="text/template" data-template="customer">
    <div class="customer" customer="${id}">
        <div class="customer-container">
            <div class="customer-section">
                <img src="${avatar}" class="image">
            </div>
            <div class="customer-section">
                ${name}
            </div>
        </div>
    </div>
</script>

<script type="text/template" data-template="room">
    <div class="room drop-shadow" typeid="${id}">
        <div class="details">
            <div class="group-input">
                <input name="name" class="name-input" type="text" value="${name}"/>
            </div>
            ${room}
        </div>
        <div class="beds">
            ${beds}
        </div>
    </div>
</script>

<script type="text/template" data-template="room-locked">
    <div class="room drop-shadow locked" typeid="${id}">
        <div class="details">
            <div class="group-input">
                <input name="name" class="name-input" type="text" value="${name}"/>
            </div>
            ${room}
        </div>
        <div class="beds">
            ${beds}
        </div>
    </div>
</script>
@endpush

{{-- JavaScript --}}
@push('footer-stack')
<script type="text/javascript">
    manager = null;
    function initialize() {
        let templates = {
            'room': template('room'),
            'lockedRoom': template('room-locked'),
            'bed': template('bed'),
            'customer': template('customer'),
        };
        let sections = {
            'rooms': $('.manager'),
            'customers': $('.customers'),
        }
        let parameters = { __api_token: '{{ \Auth::user()->getCurrentToken()->token }}', }

        occupancy.generate('{{ route('api.orders.rooming.get', ['order' => $order,]) }}', parameters, templates, sections, initiateUI).then((rooming) => {
            manager = rooming;
        });
    }
    function initiateUI() {
        $('.customer:not(.locked)').draggable({ revert: 'invalid', });
        $('.customers:not(.locked)').droppable({
            drop: function(e, ui) {
                $(e.target).append($(ui.draggable).detach().css({'top':'','left':''}));
            }
        })
    }
    $(document).ready(() => {
       initialize();
    });
</script>
@endpush
