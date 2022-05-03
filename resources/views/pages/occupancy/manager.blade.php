@extends('layout.master')

@section('title', 'Occupancy Manager')

@push('header-stack')
    <style>
        :root {
            --customer-box-size: 100px;
            --bed-size: calc(var(--customer-box-size) + 2px);
            --shadow-color: #808080;
            --section-color: #9ccff6;
            --internal-section-color: #7dabd5;
            --bed-color: #bfbaba;
            --customer-color: white;
            --shallow-radius: 10px;
            --image-size: 50px;
            --margin: 10px;
            --padding: 5px;
        }
        .drop-shadow {
            box-shadow: -2px 2px 5px var(--shadow-color);
        }
        .section-box {
            border-radius: var(--shallow-radius);
            background: var(--section-color);
            width: calc(100% - 20px);
            padding: 5px;
            margin: var(--margin);
        }
        .customers {
            min-height: 120px;
            display: flex;
            max-width: 100%;
            flex-wrap: wrap;
            gap: var(--margin);
        }
        .customers > .customer {
            box-shadow: -2px 2px 5px var(--shadow-color);
        }
        .manager {
            height: auto;
            min-height: 300px;
        }
        .customer {
            border-radius: var(--shallow-radius);
            background-color: var(--customer-color);
            min-width: var(--customer-box-size);
            width: var(--customer-box-size);
            max-width: var(--customer-box-size);
            height: var(--customer-box-size);
        }
        .customer-container {
            display: grid;
            justify-items: center;
            align-items: center;
            padding: var(--padding);
            text-wrap: normal;
        }
        .customer:hover {
            filter: brightness(95%);
            cursor: pointer;
        }
        .customer-section {
            width: 100%;
            display: flex;
            justify-content: center;
            align-content: center;
            text-align: center;
        }
        .room {
            display: flex;
            flex-wrap: wrap;
            padding: var(--padding);
            width: calc(100% - 10px);
            margin: var(--margin) calc(var(--margin) / 2);
            border-radius: var(--shallow-radius);
            background-color: var(--internal-section-color);
        }
        .beds {
            display: flex;
            max-width: 60vw;
            flex-wrap: wrap;
            gap: var(--margin);
        }
        .bed {
            border-radius: var(--shallow-radius);
            width: var(--bed-size);
            min-width: var(--bed-size);
            height: var(--bed-size);
            display: inline-block;
            border: 1px dashed black;
        }
        .image {
            width: var(--image-size);
            height: var(--image-size);
            border-radius: 50%;
        }
        .details {
            display: inline-block;
        }
        .group-input {
            display:block;
        }
        .round { border-radius: var(--shallow-radius); }
    </style>
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
            return `<div class="customer" customer="${id}"><div class="customer-container"><div class="customer-section"><img src="${avatar}" class="image"></div><div class="customer-section">${name}</div></div></div>`;
        }
        function createRoomBox(id, name, roomName, size, customers = null) {
            let bedString = '';
            if (customers != null) {
                for (let customer in customers) {
                    let customerBox = createCustomerBox(customers[customer]['id'], customers[customer]['name'], customers[customer]['avatar']);
                    bedString += `<div class="bed">${customerBox}</div>`
                }
                if (customers.length < size) {
                    for (let i = 0; i < size - customers.length; i++) {
                        bedString += `<div class="bed"></div>`
                    }
                }
            } else {
                for (let i = 0; i < size; i++) {
                    bedString += `<div class="bed"></div>`
                }
            }
            return `
        <div class="room drop-shadow" typeid="${id}">
            <div class="details">
                <div class="group-input">
                    <input name="name" class="name-input" type="text" value="${name}"/>
                </div>
                ${roomName}
            </div>
            <div class="beds">
                ${bedString}
            </div>
        </div>`;
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
                $(this).children('.beds').first().children('.bed').each(function (index) {
                    if ($(this).children().first().attr('customer') === undefined) return;
                    roomedCustomers.push($(this).children().first().attr('customer'));
                });
                roomingData.push({name: name, roomType: $(this).attr('typeid'), customers: roomedCustomers})
            }
        );
        let request = $.post({
            url: "{{ route('api.roomings.save', ['order' => $order,]) }}",
            dataType: "json",
            data: { "__api_token": '{{ Auth::user()->getCurrentToken()->token }}', "data": roomingData, },
            statusCode: {
                200: function(xhr) { alert('Success'); },
                500: function(xhr) { alert('Failed: ' + xhr.message); }
            }
        });
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
