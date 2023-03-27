customerBox = $('.customers');
manager = $('.manager');
const bed = template("bed");
const lockedBed = template("bed-locked");
const customer = template("customer");
const lockedCustomer = template("customer-locked");
const room = template("room");

function createCustomerBox(id, name, avatar) {
    return render(customer, {id: id, name: name, avatar: avatar});
}

function createRoomBox(id, name, roomName, size, customers = null, locked = false) {
    let bedString = '';
    if (customers != null) {
        for (let customer in customers) {
            let customerBox = createCustomerBox(customers[customer]['id'], customers[customer]['name'], customers[customer]['avatar']);
            bedString += createBed(customerBox, locked)
        }
        if (customers.length < size) {
            for (let i = 0; i < size - customers.length; i++) {
                bedString += createBed("", locked)
            }
        }
    } else {
        for (let i = 0; i < size; i++) {
            bedString += createBed("", locked)
        }
    }
    return render(room, {id: id, name: name, room: roomName, beds: bedString});
}

function createBed(content = "", locked = false) {
    if (locked) {
        return render(bed, {content: content,});
    }
    return render(lockedBed, {content: content,});
}

function reset() {
    customerBox.empty();
    manager.empty();
    load();
}

function load() {
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
    manager.append(roomBox);
    $('.bed:not(.locked)').droppable({
        accept: function (element) {
            return !$(this).is(':parent');
        },
        drop: function(e, ui) {
            $(e.target).append($(ui.draggable).detach().css({'top':'','left':''}));
        }
    })
}

function submit() {
    if (customerBox.is(':parent')) return alert("Not all customers are assigned!");
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
    $('.customer:not(.locked)').draggable({ revert: 'invalid', });
    $('.customers:not(.locked)').droppable({
        drop: function(e, ui) {
            $(e.target).append($(ui.draggable).detach().css({'top':'','left':''}));
        }
    })
});
