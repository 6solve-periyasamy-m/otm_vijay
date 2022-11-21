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
            <div class="customers section-box drop-shadow col-12 droppable"></div>
            <div class="col-12" style="margin: 10px;">
                <select class="room-types"></select>
                <a href="#" class="btn btn-danger round" onclick="reset()">Reset</a>
                <a href="#" class="btn btn-warning round" onclick="addGroup()">Add Room</a>
                <a href="#" class="btn btn-success round" onclick="submit()">Save</a>
                <a href="{{ route('orders.view', ['order' => $order,]) }}" class="btn btn-info round">Return to Order</a>
            </div>
            <div class="manager section-box drop-shadow"></div>
        </div>
    </div>
@endsection

{{-- Templates --}}
@push('footer-stack')
<script type="text/template" data-template="bed">
    <div class="bed droppable single">${content}</div>
</script>

<script type="text/template" data-template="room-option">
    <option value="${id}">${name} - Size: ${size}</option>
</script>

<script type="text/template" data-template="customer">
    <div class="customer draggable" customer="${id}">
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
    <div class="room drop-shadow" roomid="${id}">
        <div class="details">
            <span class="fw-bold">${name}</span>
        </div>
        <div class="beds">
            ${beds}
        </div>
    </div>
</script>

<script type="text/template" data-template="room-locked">
    <div class="room drop-shadow locked" roomid="${id}">
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
    const selector = $('.room-types');
    const customers = $('.customers');
    const rooms = $('.manager');
    data = null;
    function initialize() {
        let parameters = { __api_token: '{{ \Auth::user()->getCurrentToken()->token }}', }

        occupancy.generate('{{ route('api.orders.rooming.get', ['order' => $order,]) }}', parameters).then((rooming) => {
            data = rooming;
            loadData();
            setupDragDrop();
        });
    }

    function renderCustomer(customer) {
        return render(template('customer'), {id: customer.id, name: customer.name, avatar: customer.avatar, })
    }

    function renderGroup(group) {
        let room = data.getRoom(group.room);
        let beds = "";
        let counter = room.size;
        for (const customer of group.customers) {
            beds += renderBed(customer);
            counter--;
        }
        for (;counter > 0; counter--) {
            beds += renderBed();
        }
        return render(template('room'), {beds: beds, id: group.id, name: group.name})
    }

    function renderBed(customer = null) {
        let content = "";
        if (customer !== null) {
            content = renderCustomer(customer);
        }
        return render(template('bed'), {content: content,})
    }

    function loadData() {
        if (data !== null) {
            for (const room of data.getRooms()) {
                selector.append(render(template('room-option'), {id: room.id, name: room.name, size: room.size,}))
            }
            for (const customer of data.getOrphanedCustomers()) {
                customers.append(renderCustomer(customer));
            }
            for (const group of data.getGroups()) {
                rooms.append(renderGroup(group));
            }
        }
    }

    function setupDragDrop() {
        $('.draggable:not(.locked)').draggable({ revert: 'invalid', });
        $('.droppable:not(.locked, .single)').droppable({
            drop: function(e, ui) {
                processDropEvent($(ui.draggable), e.target)
            }
        })
        $('.droppable.single:not(.locked)').droppable({
            accept: function (element) {
                return !$(this).is(':parent');
            },
            drop: function(e, ui) {
                processDropEvent($(ui.draggable), e.target)
            }
        });
    }

    function processDropEvent(element, target) {
        element = $(element);
        target = $(target);
        let old = element.closest('.room');
        $(target).append(element.detach().css({'top':'','left':''}));
        let customer = element.attr('customer');
        if (data !== null) {
            if (old !== null) {
                data.removeFromGroup(old.attr('roomid'), customer);
            }
            if (target.hasClass('bed')) {
                let room = $(target).closest('.room');

                data.addToGroup(room.attr('roomid'), customer)
            }
            console.log(data.getGroups());
        }
    }

    function addGroup() {
        let type = selector.find(':selected');
        if (data !== null) {
            let group = data.addGroup(parseInt(type.val()));
            rooms.append(renderGroup(group));
        }
        setupDragDrop();
    }

    function reset() {
        customers.empty();
        rooms.empty();
        data.reset();
        loadData();
        setupDragDrop()
    }

    function submit() {
        if (customers.children().length > 0) {
            return alert('Not all customers have rooms');
        }
        let parameters = { __api_token: '{{ \Auth::user()->getCurrentToken()->token }}', }
        data.save('{{ route('api.roomings.save', ['order' => $order,]) }}', parameters).then((success) => {
            if (success) {
                alert('Data has been saved');
            } else {
                alert('Data failed to save');
            }
        });
    }

    $(document).ready(() => {
       initialize();
    });
</script>
@endpush
