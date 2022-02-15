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
            </div>
            <div class="manager droppable"></div>
        </div>
    </div>
@endsection

{{-- Initialize JavaScript Variables --}}
@push('footer-stack')
    <script type="text/javascript">
        let customerBox = $('.customers');
        let manager = $('.manager');
        let customers = {
            @foreach($unused as $data)
                    {{ $data['id'] }}: {'name': '{{ $data['name'] }}', 'avatar': '{{ $data['avatar'] }}'},
            @endforeach
            @foreach($groups as $group)
                @if(key_exists('customers', $group))
                    @foreach($group['customers'] as $data)
                        {{ $data['id'] }}: {'name': '{{ $data['name'] }}', 'avatar': '{{ $data['avatar'] }}'},
                    @endforeach
                @endif
            @endforeach
        };
    </script>
@endpush

{{-- Builder Functions --}}
@push('footer-stack')
    <script type="text/javascript">
        function createCustomerBox(id, name, avatar) {
            return '<div class="customer customer-' + id + '" customer="' + name + '"><img src="' + avatar + '" class="image"/><br />' + name + '</div>';
        }
        function createRoomBox(id, name, size, customers = null) {
            let bedString = '';
            if (customers != null) {
                for (let customer in customers) {
                    bedString += '<div class="bed">' + createCustomerBox(customer, customers[customer]['name'], customers[customer]['avatar']) + '</div>'
                }
            } else {
                for (let i = 0; i < size; i++) {
                    bedString += '<div class="bed"></div>'
                }
            }
            return '<div class="room" typeid="' + id + '">' + name + bedString + '</div>';
        }
    </script>
@endpush

{{-- Functionality --}}
@push('footer-stack')
<script type="text/javascript">
    function initialize() {
        let groups = {
                @foreach($groups as $group)
                    [
                    @if(key_exists('customers', $group))
                        @foreach($group['customers'] as $data)
                            { {{ $data['id'] }}: {'name': '{{ $data['name'] }}', 'avatar': '{{ $data['avatar'] }}'} },
                        @endforeach
                    @endif
                    ]
                @endforeach
        }
    }
    function reset() {
        customerBox.empty();
        manager.empty();
        build();
    }
    function build() {
        for (let customer in customers) {
            customerBox.append(createCustomerBox(customer, customers[customer]['name'], customers[customer]['avatar']));
        }
        $('.customer').draggable({ revert: 'invalid', });
    }

    function addRoom() {
        let selected = $('.room-types').find(':selected')
        if ($('.bed').length + parseInt(selected.val()) > Object.keys(customers).length) return alert('Cannot add more rooms!');
        manager.append(createRoomBox(selected.val(), selected.attr('name'), selected.attr('occupancy')))
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
        let alrt = '';
        $('.room').each(function (index) {
                let data = $(this).attr('typeid') + ': ';
                $(this).children('.bed').each(function (index) {
                    data += $(this).children().first().attr('customer') + ', ';
                })
                alrt += data + "\n";
            }
        )
        alert(alrt);
    }
    $(document).ready(function () {
        build();
        customerBox.droppable({
            drop: function(e, ui) {
                $(e.target).append($(ui.draggable).detach().css({'top':'','left':''}));
            }
        })
    });
</script>
@endpush
