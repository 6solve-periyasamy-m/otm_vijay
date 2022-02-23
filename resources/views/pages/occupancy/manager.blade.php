@extends('layout.master')

@section('title', 'Occupancy Manager')

@push('header-stack')
    <style>
        .manager {
            border: 1px solid black;
            width: 100%;
            height: auto;
            min-height: 300px;
            padding: 10px
        }
        .customers {
            border: 1px solid black;
            width: 100%;
            padding: 5px;
            min-height: 120px;
        }
        .customer {
            display: inline-block;
            padding: 2px;
            border: 1px solid black;
            width: fit-content;
            max-width: 100px;
            height: 100px;
            margin: 2px;
            text-wrap: normal;
        }
        .room {
            border: 1px solid black;
            padding: 5px;
            width: 100%;
            margin: 2px;
        }
        .bed {
            border: 1px solid black;
            padding: 2px;
            margin: 2px;
            width: 125px;
            height: 125px;
            display: inline-block;
        }
        .image {
            width: 50px;
            height: 50px;
            border-radius: 25px;
        }
        .details {
            display: inline-block;
        }
        .group-input {
            display:block;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-body col-12">
            <div class="customers col-12 col-xl-9"></div>
            <div class="col-12 col-xl-3">
                <select class="room-types">
                    @foreach($rooms as $data)
                        <option value="{{ $data['id'] }}" name="{{ $data['name'] }}" occupancy="{{ $data['size'] }}">{{ $data['name'] }} - Space: {{ $data['size'] }}</option>
                    @endforeach
                </select>
                <a href="#" class="btn btn-danger" onclick="reset()">Reset</a>
                <a href="#" class="btn btn-warning" onclick="addRoom()">Add Room</a>
                <a href="#" class="btn btn-success" onclick="submit()">Save</a>
                <a href="{{ route('orders.view', ['order' => $order,]) }}" class="btn btn-info">Return to Order</a>
            </div>
            <div class="manager droppable"></div>
        </div>
    </div>
@endsection

{{-- Initialize JavaScript Variables --}}
@push('footer-stack')
    <script type="text/javascript">
        customerBox = $('.customers');
        manager = $('.manager');
        customers = {!! json_encode($customers) !!};
    </script>
@endpush

{{-- Builder Functions --}}
@push('footer-stack')
    <script type="text/javascript">
        function createCustomerBox(id, name, avatar) {
            return '<div class="customer customer-' + id + '" customer="' + id + '"><img src="' + avatar + '" class="image"/><br />' + name + '</div>';
        }
        function createRoomBox(id, name, roomName, size, customers = null) {
            let bedString = '';
            if (customers != null) {
                for (let customer in customers) {
                    bedString += '<div class="bed">' + createCustomerBox(customers[customer]['id'], customers[customer]['name'], customers[customer]['avatar']) + '</div>'
                }
            } else {
                for (let i = 0; i < size; i++) {
                    bedString += '<div class="bed"></div>'
                }
            }
            return '<div class="room" typeid="' + id + '"><div class="details"><div class="group-input"><input name="name" class="name-input" type="text" value="' + name + '"/></div>' + roomName + "</div>" + bedString + '</div>';
        }
    </script>
@endpush

{{-- Functionality --}}
@push('footer-stack')
<script type="text/javascript">
    function initialize() {
        let groups = [
            @foreach ($groups as $group)
            {name: "{{ $group['name'] }}", roomType: {name: "{{ $group['roomType']['name'] }}", id: {{ $group['roomType']['id'] }}, size: {{ $group['roomType']['size'] }}},
             customers: [
                 @foreach ($group['customers'] as $customer)
                 {name: "{{$customer['name']}}", id: {{$customer['id']}}, avatar: "{{$customer['avatar']}}",},
                 @endforeach
             ]},
            @endforeach
        ];
        for (let groupid in groups) {
            addRoomToManager(createRoomBox(groups[groupid]['roomType']['id'], groups[groupid]['name'], groups[groupid]['roomType']['name'],groups[groupid]['roomType']['size'], groups[groupid]['customers'],));
        }
        let unused = {!! json_encode($unused) !!};
        for (let key in {!! json_encode($unused) !!}) {
            customerBox.append(createCustomerBox(unused[key]['id'], unused[key]['name'], unused[key]['avatar']));
        }
    }
    function reset() {
        customerBox.empty();
        $('.manager').empty();
        build();
    }
    function build() {
        for (let key in customers) {
            customerBox.append(createCustomerBox(customers[key]['id'], customers[key]['name'], customers[key]['avatar']));
        }
        $('.customer').draggable({ revert: 'invalid', });
    }

    function addRoom() {
        let selected = $('.room-types').find(':selected')
        if ($('.bed').length + parseInt(selected.val()) > Object.keys(customers).length) return alert('Cannot add more rooms!');
        addRoomToManager(createRoomBox(selected.val(), "Group " + $('.room').length, selected.attr('name'), selected.attr('occupancy')))
    }
    function addRoomToManager(roomBox) {
        $('.manager').append(roomBox);
        $('.bed').droppable({
            accept: function (element) {
                return !$(this).is(':parent');
            },
            drop: function(e, ui) {
                $(e.target).append($(ui.draggable).detach().css({'top':'','left':''}));
            }
        })
    }

    function submit() {
        if ($('.customers').is(':parent')) return alert("Not all customers are assigned!");
        let roomingData = [];
        $('.room').each(function (index) {
                let roomedCustomers = [];
                let name = $(this).find('.name-input').val();
                console.log(name);
                $(this).children('.bed').each(function (index) {
                    roomedCustomers.push($(this).children().first().attr('customer'));
                });
                roomingData.push({name: name, roomType: $(this).attr('typeid'), customers: roomedCustomers})
            }
        );
        console.log(roomingData);
        let request = $.post({
            url: "{{ route('api.roomings.save', ['order' => $order,]) }}",
            dataType: "json",
            data: { "__api_token": '{{ Auth::user()->getCurrentToken()->token }}', "data": roomingData, },
            statusCode: {
                200: function(xhr) { alert('Success'); },
                500: function(xhr) { alert('Failed'); }
            }
        });
        console.log(roomingData);
    }
    $(document).ready(function () {
        initialize();
        $('.customer').draggable({ revert: 'invalid', });
        $('.customers').droppable({
            drop: function(e, ui) {
                $(e.target).append($(ui.draggable).detach().css({'top':'','left':''}));
            }
        })
    });
</script>
@endpush
