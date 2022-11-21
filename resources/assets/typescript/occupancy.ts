class Customer {
    id: number;
    name: string;
    avatar: string;

    constructor(id: number, name: string, avatar: string) {
        this.id = id;
        this.name = name;
        this.avatar = avatar;
    }
}

class Room {
    id: number;
    name: string;
    size: number;

    constructor(id: number, name: string, size: number) {
        this.id = id;
        this.name = name;
        this.size = size;
    }
}

class Group {
    id: number
    name: string;
    customers: Customer[];
    room: number;

    constructor(id: number, name: string, room: number, customers: Customer[]) {
        this.id = id;
        this.name = name;
        this.customers = customers;
        this.room = room;
    }
}

class RoomingData {
    groups: Group[];
    customers: Customer[];
    rooms: Room[];

    constructor(rooms: Room[], customers: Customer[], groups: Group[]) {
        this.groups = groups;
        this.customers = customers;
        this.rooms = rooms;
    }
}

interface RemoteRoom {
    name: string;
    size: number;
}

interface RemoteCustomer {
    name: string;
    avatar: string;
}

interface RemoteGroup {
    name: string;
    room: number;
    customers: number[];
}

interface RemoteRoomingData {
    rooms: RemoteRoom[];
    customers: RemoteCustomer[];
    groups: RemoteGroup[];
}

async function generateRoomingManager(url: string, parameters: Object  = {}): Promise<RoomingData> {
    let data :RemoteRoomingData = await $.post({
        url: url,
        dataType: "json",
        data: parameters,
    });
    let customers: Customer[] = [];
    for (const id of Object.keys(data.customers)) {
        let nId: number = ((id as unknown) as number);
        customers.push(new Customer(nId, data.customers[nId].name, data.customers[nId].avatar));
    }
    let rooms = [];
    for (const id of Object.keys(data.rooms)) {
        let nId: number = ((id as unknown) as number);
        rooms.push(new Room(nId, data.rooms[nId].name, data.rooms[nId].size));
    }
    let groups = [];
    for (const id of Object.keys(data.groups)) {
        let nId: number = ((id as unknown) as number);
        let groupCustomers: Customer[] = [];
        for (const [key, customer] of Object.entries(customers)) {
            if (data.groups[nId].customers.includes(customer.id)) groupCustomers.push(customer);
        }
        groups.push(new Group(nId, data.groups[nId].name, data.groups[nId].room, groupCustomers));
    }
    return new RoomingData(rooms, customers, groups);
}

(window as any).occupancy = {};
(window as any).occupancy.generate = generateRoomingManager;
