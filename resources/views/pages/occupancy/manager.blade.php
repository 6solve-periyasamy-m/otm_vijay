@extends('layout.master')

@php
    $customers = [1 => 'John', 2 => 'Paul', 3 => 'George', 4 => 'Ringo', 5 => 'Yoko'];
    $roomTypes = ['Double' => 2, 'Single' => 1, 'Triple' => 3];
@endphp

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
            min-width: 75px;
            height: 75px;
            margin: 2px;
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
            width: 100px;
            height: 100px;
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
                    @foreach($roomTypes as $name => $size)
                        <option value="{{ $size }}">{{ $name }}</option>
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

@push('footer-stack')
<script type="text/javascript">
    let customerBox = $('.customers');
    let manager = $('.manager');
    let customers = {@foreach($customers as $id => $name) {{ $id }}: '{{ $name }}', @endforeach}
    function reset() {
        customerBox.empty();
        manager.empty();
        build();
    }
    function build() {
        for (let customer in customers) {
            customerBox.append('<div class="customer customer-' + customer + '" customer="' + customers[customer] + '"><img src="{{ asset('images/exampleavatar.jpg') }}" class="image"></img><br />' + customers[customer] + '</div>');
        }
        $('.customer').draggable({ revert: 'invalid', });
    }
    function addRoom() {
        let selected = $('.room-types').find(':selected')
        if ($('.bed').length + parseInt(selected.val()) > Object.keys(customers).length) return alert('Cannot add more rooms!');
        let bedString = '';
        for (let i = 0; i < selected.val(); i++) {
            bedString += '<div class="bed"></div>'
        }
        manager.append('<div class="room" typeid="' + selected.val() + '">' + selected.text() + bedString + '</div>')
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
