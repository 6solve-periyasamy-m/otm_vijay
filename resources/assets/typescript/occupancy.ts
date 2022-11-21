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

    public addCustomer(customer: Customer) {
        this.customers.push(customer);
    }

    public removeCustomer(customer: Customer) {
        this.customers = removeElement<Customer>(this.customers, customer, (obj1, obj2) => { return obj1.id == obj2.id; })
    }
}

class RoomingData {
    private groups: Group[];
    private readonly customers: Customer[];
    private readonly rooms: Room[];

    constructor(rooms: Room[], customers: Customer[], groups: Group[]) {
        this.groups = groups;
        this.customers = customers;
        this.rooms = rooms;
    }

    public getRooms(): Room[] {
        return this.rooms;
    }

    public getRoom(id: number): Room | null {
        for (const room of this.rooms) {
            if (((room.id as unknown) as number) == id) return room;
        }
        return null;
    }

    public getGroups(): Group[] {
        return this.groups;
    }

    public getOrphanedCustomers(): Customer[] {
        let owned = [];
        for (const group of this.groups) {
            for (const customer of group.customers) {
                owned.push(customer.id);
            }
        }
        let unowned: Customer[] = [];
        for (const customer of this.customers) {
            if (owned.includes(customer.id)) continue;
            unowned.push(customer);
        }
        return unowned;
    }

    public getGroup(id: number): Group | null {
        for (const group of this.groups) {
            if (group.id == id) return group;
        }
        return null;
    }

    public removeFromGroup(groupId: number, customerId: number) {
        let group = this.getGroup(groupId);
        let customer = this.getCustomer(customerId);
        if (group !== null && customer !== null) {
            group.removeCustomer(customer);
        }
    }

    public addToGroup(groupId: number, customerId: number) {
        let group = this.getGroup(groupId);
        let customer = this.getCustomer(customerId);
        if (group !== null && customer !== null) {
            group.addCustomer(customer);
        }
    }

    public getCustomers(): Customer[] {
        return this.customers;
    }

    public getCustomer(id: number): Customer | null {
        for (const customer of this.customers) {
            if (customer.id == id) return customer;
        }
        return null;
    }

    public reset(): void {
        this.groups = [];
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

interface EquivalenceCallback<T> {
    (obj1: T, obj2: T): boolean;
}

function removeElement<T>(array: T[], item: T, callback: EquivalenceCallback<T>|null): T[] {
    for (let i = 0; i < array.length; i++) {
        let equal = false;
        if (callback !== null) {
            equal = callback(array[i], item);
        } else {
            equal = array[i] == item;
        }
        if (equal) {
            array.splice(i, 1);
        }
    }
    return array;
}

async function generateRoomingManager(url: string, parameters: Object = {}): Promise<RoomingData> {
    let data: RemoteRoomingData = await $.post({
        url: url,
        dataType: "json",
        data: parameters,
    });
    let customers: Customer[] = [];
    for (const id of Object.keys(data.customers)) {
        let nId: number = parseInt(id);
        customers.push(new Customer(nId, data.customers[nId].name, data.customers[nId].avatar));
    }
    let rooms = [];
    for (const id of Object.keys(data.rooms)) {
        let nId: number = parseInt(id);
        rooms.push(new Room(nId, data.rooms[nId].name, data.rooms[nId].size));
    }
    let groups = [];
    for (const id of Object.keys(data.groups)) {
        let nId: number = parseInt(id);
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
