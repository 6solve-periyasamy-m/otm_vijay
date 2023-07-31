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
    price: number;
    start: Date;
    end: Date;
    available: boolean;

    constructor(id: number, name: string, price: number, size: number, start: Date, end: Date, available: boolean = true) {
        this.id = id;
        this.name = name;
        this.size = size;
        this.price = price;
        this.start = new Date(start.setHours(0, 0, 0));
        this.end = new Date(end.setHours(23, 59, 59));
        this.available = available;
    }

    public containsDate(date: Date): boolean {
        let start = new Date(new Date(this.start).setHours(0,0,0));
        let end = new Date(new Date(this.end).setHours(0,0,0));
        return start <= date && date < end;
    }
}

class Group {
    id: number
    name: string;
    customers: Customer[];
    room: Room;

    constructor(id: number, room: Room, customers: Customer[]) {
        this.id = id;
        this.name = room.name + " - Size: " + room.size;
        this.customers = customers;
        this.room = room;
    }

    public addCustomer(customer: Customer) {
        this.customers.push(customer);
    }

    public removeCustomer(customer: Customer) {
        this.customers = removeElement<Customer>(this.customers, customer, (obj1, obj2) => {
            return obj1.id == obj2.id;
        })
    }

    public getCustomerIds() {
        let ids: number[] = [];
        for (const customer of this.customers) {
            ids.push(customer.id);
        }
        return ids;
    }

    public isOnNight(night: Date): boolean {
        return this.room.containsDate(night);
    }

    public editable(night: Date): boolean {
        let start = new Date(this.room.start.valueOf()).setHours(0,0,0);
        return night.setHours(0,0,0) == start;
    }
}

class RoomingData {
    private groups: Group[];
    private readonly customers: Customer[];
    private readonly rooms: Room[];
    private readonly start: Date;
    private readonly end: Date;

    constructor(rooms: Room[], customers: Customer[], groups: Group[]) {
        this.groups = groups;
        this.customers = customers;
        this.rooms = rooms;
        let start: Date | null = null;
        let end: Date | null = null;
        for (const room of this.rooms) {
            if (start == null ||
                start.getTime() > new Date(room.start.valueOf()).setHours(0, 0, 0)) {
                start = new Date(room.start.valueOf());
            }
            if (end == null ||
                end.getTime() < new Date(room.start.valueOf()).setHours(23, 59, 59)) {
                end = new Date(room.start.valueOf());
            }
        }
        this.start = start ?? new Date();
        this.end = end ?? new Date();
    }

    public getRooms(night: Date | null = null): Room[] {
        if (night !== null) {
            let rooms: Room[] = [];
            for (const room of this.rooms) {
                if (room.containsDate(night)) rooms.push(room);
            }
            return rooms;
        }
        return this.rooms;
    }

    public getRoom(id: number): Room | null {
        for (const room of this.rooms) {
            if (((room.id as unknown) as number) == id) return room;
        }
        return null;
    }

    public getGroups(night: Date|null = null): Group[] {
        if (night !== null) {
            let groups = [];
            for (const group of this.groups) {
                if (group.isOnNight(night)) groups.push(group);
            }
            return groups;
        }
        return this.groups;
    }

    public getOrphanedCustomers(date: Date|null): Customer[] {
        let groups = this.getGroups(date);
        let owned = [];
        for (const group of groups) {
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

    public addGroup(room: number): Group | null {
        let found = false;
        let id = Date.now();
        do {
            for (const group of this.groups) {
                if (group.id == id) {
                    found = true;
                    break;
                }
            }
            if (found) id++;
        } while (found)
        let type = this.getRoom(room);
        if (type == null) return null;
        let group = new Group(id, type, []);
        this.groups.push(group);
        return group;
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

    public getStartDate(): Date {
        return this.start;
    }

    public getEndDate(): Date {
        return this.end;
    }

    public getNights(): Date[] {
        // @ts-ignore
        return this.start.range(this.end);
    }

    public reset(date: Date|null): void {
        if (date !== null) {
            for (const group of this.getGroups(date)) {
                if (group.editable(date)) {
                    removeElement(this.groups, group, (group1, group2) => group1.room.id == group2.room.id);
                }
            }
            return;
        }
        this.groups = [];
    }

    public async save(url: string, parameters: Record<string, any> = {}): Promise<boolean> {
        parameters.data = this.formatObject();
        let results = await $.post({
            url: url,
            dataType: "json",
            data: parameters,
        });
        if (results.success == true) {
            return true;
        } else {
            console.error(results.msg);
            return false;
        }
    }

    private formatObject(): ExportedGroup[] {
        let data: ExportedGroup[] = [];
        for (const group of this.groups) {
            if (group.customers.length <= 0) continue;
            data.push(new ExportedGroup(group.room.id, group.getCustomerIds()))
        }
        return data;
    }
}

interface RemoteRoom {
    price: number;
    start: number;
    end: number;
    name: string;
    size: number;
    available: boolean|null;
}

interface RemoteCustomer {
    name: string;
    avatar: string;
}

interface RemoteGroup {
    name: string;
    rooms: number[];
    customers: number[];
}

interface RemoteRoomingData {
    rooms: RemoteRoom[];
    customers: RemoteCustomer[];
    groups: RemoteGroup[];
}

class ExportedGroup {
    rooms: number[];
    customers: number[];

    constructor(room: number, customers: number[]) {
        this.rooms = [room,];
        this.customers = customers;
    }
}

interface EquivalenceCallback<T> {
    (obj1: T, obj2: T): boolean;
}

function removeElement<T>(array: T[], item: T, callback: EquivalenceCallback<T> | null): T[] {
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
        rooms.push(new Room(nId, data.rooms[nId].name, data.rooms[nId].price, data.rooms[nId].size, new Date(data.rooms[nId].start * 1000), new Date(data.rooms[nId].end * 1000), data.rooms[nId].available ?? true));
    }
    let groups = [];
    for (const id of Object.keys(data.groups)) {
        let nId: number = parseInt(id);
        let groupCustomers: Customer[] = [];
        for (const [key, customer] of Object.entries(customers)) {
            if (data.groups[nId].customers.includes(customer.id)) groupCustomers.push(customer);
        }
        for (const [key, room] of Object.entries(rooms)) {
            if (data.groups[nId].rooms.includes(room.id)) {
                groups.push(new Group(nId, room, groupCustomers));
            }
        }
    }
    return new RoomingData(rooms, customers, groups);
}

(window as any).occupancy = {};
(window as any).occupancy.generate = generateRoomingManager;

(Date as any).prototype.addDays = function (days: number): Date {
    this.setDate(this.getDate() + days);
    return this;
};

(Date as any).prototype.range = function (end: Date, inclusive: boolean = true): Date[] {
    let dates = [];
    let start = new Date(this.setHours(0, 0, 0).valueOf());
    if (inclusive) {
        end = new Date(end.setHours(23, 59, 59));
    } else {
        end = new Date(end.setHours(0, 0, 0));
    }
    while (start <= end) {
        dates.push(new Date(start.valueOf()))
        // @ts-ignore
        start.addDays(1);
    }
    return dates;
}
