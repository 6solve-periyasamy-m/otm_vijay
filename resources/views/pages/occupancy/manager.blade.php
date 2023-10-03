@extends('layout.master', ['tailwind' => false])

@section('title', 'Occupancy Manager')

@push('footer-stack')
    <!--suppress HtmlUnknownTarget -->
    <link href="{{ asset('css/admin/occupancy.css') }}" type="text/css" rel="stylesheet" />
    <script src="{{ asset('js/admin/functions.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/occupancy.js') }}" type="text/javascript"></script>
@endpush

@section('content')
    <div class="card">
        <div class="card-body col-12">
            <div class="date-switcher">
                <button class="btn border-dark bg-white" onclick="changeDay(-1)">&lt;</button>
                <button class="btn border-dark bg-white active-date">Waiting for Data</button>
                <button class="btn border-dark bg-white" onclick="changeDay(1)">&gt;</button>
            </div>
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
    <option value="${id}">${name} - ${price}</option>
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
            <span class="fw-bold">${name} - ${price}</span>
        </div>
        <div class="beds">
            ${beds}
        </div>
    </div>
</script>

<script type="text/template" data-template="room-locked">
    <div class="room drop-shadow locked" roomid="${id}">
        <div class="details">
            <span class="fw-bold">${name} - ${price} (Locked, Editable on ${editable})</span>
        </div>
        <div class="beds">
            ${beds}
        </div>
    </div>
</script>

<script type="text/template" data-template="loader">
    <div class="waiter">
        <x-loading-spinner></x-loading-spinner>
    </div>
</script>
@endpush

{{-- JavaScript --}}
@push('footer-stack')
<script type="text/javascript">
    const selector = $('.room-types');
    const customers = $('.customers');
    const rooms = $('.manager');
    const activeDate = $('.active-date');
    data = null;
    window.date = null;
    function initialize() {
        let parameters = { __api_token: '{{ \Auth::user()->getCurrentToken()->token }}', }

        occupancy.generate('{{ route('api.orders.rooming.get', ['order' => $order,]) }}', parameters).then((rooming) => {
            data = rooming;
            loadData(true);
            setupDragDrop();
        });
    }

    function renderCustomer(customer) {
        return render(template('customer'), {
            id: customer.id,
            name: customer.name,
            avatar: customer.avatar,
        });
    }

    function renderGroup(group) {
        let beds = "";
        let counter = group.room.size;
        for (const customer of group.customers) {
            beds += renderBed(customer);
            counter--;
        }
        for (;counter > 0; counter--) {
            beds += renderBed();
        }
        let price = group.room.price > 0 ? formatCurrency(group.room.price) : 'Included';
        let locked = !group.editable(window.date);
        if (locked) {
            return render(template('room-locked'), {
                beds: beds,
                id: group.id,
                name: group.name,
                price: price,
                roomId: group.room.id,
                editable: group.room.start.toLocaleDateString()
            });
        }
        return render(template('room'), {
            beds: beds,
            id: group.id,
            name: group.name,
            price: price,
            roomId: group.room.id
        });
    }

    function renderBed(customer = null) {
        let content = "";
        if (customer !== null) {
            content = renderCustomer(customer);
        }
        return render(template('bed'), {content: content,})
    }

    function updateDate() {
        activeDate.text("Night of " + window.date.toLocaleDateString());
        updateSelector(data.getRooms(window.date));
        updateRooms(data.getGroups(window.date));
        updateOrphans(data.getOrphanedCustomers(window.date));
        setupDragDrop();
    }

    function updateRooms(groups) {
        rooms.empty();
        for (const group of groups) {
            rooms.append(renderGroup(group));
        }
    }

    function updateOrphans(orphans) {
        customers.empty();

        for (const customer of orphans) {
            customers.append(renderCustomer(customer));
        }
    }

    function changeDay(days) {
        if (data === null) return;
        let newDate = new Date(window.date.valueOf());
        newDate.addDays(days);
        if (newDate > data.getEndDate() || newDate < data.getStartDate()) {
            return;
        }
        window.date = newDate;
        updateDate();
    }

    function updateSelector(rooms) {
        selector.empty();
        for (const room of rooms) {
            let price = room.price > 0 ? formatCurrency(room.price) : 'Included';
            selector.append(render(template('room-option'), {
                id: room.id,
                name: room.name,
                size: room.size,
                price: price
            }));
        }
    }

    function loadData(date = false) {
        if (data !== null) {
            if (date) {
                window.date = data.getStartDate();
            }
            updateDate();
        }
    }

    function setupDragDrop() {
        $('.draggable:not(.locked)').filter(function (e) { return $(this).parents('.locked').length == 0; }).draggable({ revert: 'invalid', });
        $('.droppable:not(.locked, .single)').filter(function (e) { return $(this).parents('.locked').length == 0; }).droppable({
            drop: function(e, ui) {
                processDropEvent($(ui.draggable), e.target)
            }
        })
        $('.droppable.single:not(.locked)').filter(function (e) { return $(this).parents('.locked').length == 0; }).droppable({
            accept: function (element) {
                return !$(this).is(':parent');
            },
            drop: function(e, ui) {
                processDropEvent($(ui.draggable), e.target)
            }
        });
    }

    function showSpinner() {
        $('body').append(render(template('loader'), {}));
    }

    function hideSpinner() {
        $('.waiter').remove();
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
        data.reset(window.date);
        loadData();
        setupDragDrop()
    }

    function submit() {
        showSpinner();
        let parameters = { __api_token: '{{ \Auth::user()->getCurrentToken()->token }}', }
        data.save('{{ route('api.roomings.save', ['order' => $order,]) }}', parameters).then((success) => {
            hideSpinner();
            if (success) {
                alert('Data has been saved');
            } else {
                alert('Data failed to save');
            }
        }).catch((error) => {
            console.log(error);
            hideSpinner();
            alert('Data failed to save');
        });
    }

    function formatCurrency(number) {
        let formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: '{{ setting('system.currency', 'gbp') }}'
        })
        return formatter.format(number);
    }

    $(document).ready(() => {
       initialize();
    });
</script>
@endpush
