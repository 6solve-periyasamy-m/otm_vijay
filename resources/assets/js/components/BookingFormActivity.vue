<template>
    <div class="booking-container">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-1">
                    <button class="btn btn-link cardhead" @click="toggleActivities">
                        Activities
                    </button>
                </h5>
                <p class="caption">
                    <font-awesome-icon icon="arrow-right" />
                    Activities planned as part of your package, plus available options for each traveller in your group.
                </p>
            </div>
            <div class="card-body" v-if="activated">
                <div class="listing headings">
                    <div class="column-starts-at">
                        When
                    </div>
                    <div class="column-name">
                        Details   
                    </div>
                    <div class="column-address">
                        Address 
                    </div>
                    <div class="column-component-type">
                        Type
                    </div>
                    <div class="column-ticket-type">
                        Details
                    </div>
                    <div class="column-action">
                        Action
                    </div>
                </div>
                <div class="listing" v-for="activity in activities" 
                    :key="activity.activity_inventory_tour_id"
                    :class="{greyed: activity.status == 'upgraded'}">
                    <div v-if="debug">Status {{activity.status}}</div>
                    <div class="column-starts-at">
                        {{startDate(activity)}}
                        <br>
                        {{endDate(activity)}}
                    </div>
                    <div class="column-name" :class="{blankIt: activity.tour_component_type == 'Upgrade'}">
                        {{ activity.name }}<br>{{ activity.description !== activity.name ? activity.description :''}} 
                    </div>
                    <div class="column-address" :class="{blankIt: activity.tour_component_type == 'Upgrade'}">
                        {{ activity.address != undefined && activity.address.length ? activity.address : '-'}}
                        <br>
                        {{ activity.address_region != undefined && activity.address_region.length ? activity.address_region : ''}}
                    </div>

                    <div class="column-component-note" v-if="activity.ticket_type_name == 'Basic'">Included</div>
                    <div v-else class="column-component-type emphasiseIt">
                        {{activity.status=='booked' ? activity.tour_component_type == 'Upgrade' ? 'Upgraded': '' : '' }}
                    </div>
                    <div class="column-ticket-type" :class="{emphasiseIt: activity.tour_component_type == 'Upgrade'}">
                        {{activity.ticket_type_name =='Basic' ? '' : activity.ticket_type_name}}
                    </div>
                    <div class="column-ticket-type" v-if="activity.tour_component_type == 'Upgrade'">
                        {{activity.sales_price}}
                    </div>
                    <div class="column-stock" v-if="activity.stock < 1">
                        <h3 style="color: red">OUT OF STOCK</h3>
                    </div>
                    <div class="column-action">
                        <button
                            class="btn btn-primary btn-small" 
                            v-if="activity.status == 'booked' 
                            && activity.tour_component_type === 'Upgrade'" 
                            @click="cancelActivityUpgrade(activity)">Cancel</button>
                        <button
                            v-if="activity.status!='booked' 
                            && activity.tour_component_type === 'Upgrade' 
                            && activity.stock>0" 
                            class="btn btn-primary btn-small" 
                            @click="bookActivityUpgrade(activity)">Upgrade</button>
                    </div>                    <!-- {{index}} {{selected}} {{activity.activity_inventory_tour_id}} {{selected[activity.activity_inventory_tour_id]}} -->
                </div>

                <div class="divider"><h5>Optional Additional activities</h5></div>

                <div class="listing" v-for="(activity,index) in addons" 
                    :key="activity.activity_inventory_tour_id">
                    <div v-if="debug">Status {{activity.status}} <br> Type {{activity.tour_component_type}}</div>
                    <div class="column-starts-at">
                        {{startDate(activity)}}<br>to {{endDate(activity)}}
                     </div>
                    <div class="column-name">
                        {{ activity.name }}<br>{{ activity.description !== activity.name ? activity.description :''}} 
                    </div>
                    <div class="column-address">
                        {{ activity.address != undefined && activity.address.length ? activity.address : '-'}}
                        <br>
                        {{ activity.address_region != undefined && activity.address_region.length ? activity.address_region : ''}}
                    </div>
                    <div class="column-component-type">
                        {{activity.tour_component_type}}
                    </div>
                    <div class="column-ticket-type">
                        {{activity.ticket_type_name}}
                    </div>
                    <div class="column-ticket-type">
                        {{activity.sales_price}}
                    </div>
                    <div class="column-action">
                        <button class="btn btn-primary btn-small" 
                            v-if="activity.tour_component_type === 'Add-on'
                                && activity.status != 'booked'"
                            @click="bookActivityAddon(activity)">Add-on</button>
                        <button class="btn btn-primary btn-small" 
                            v-if="activity.tour_component_type === 'Add-on'
                                && activity.status == 'booked'"
                            @click="cancelActivityAddon(activity)">Cancel</button>
                    </div>
                    <!-- {{index}} {{selected}} {{activity.activity_inventory_tour_id}} {{selected[activity.activity_inventory_tour_id]}} -->
                </div>
                <div v-if="debug">
                     {{addons}}
                </div>
                <div>
                    <button class="btn btn-primary" @click="completeActivities">Complete</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import dates from '../utilities'
import { bus } from '../bus'
import axios from 'axios'
import Vue from 'vue'
export default {
    props: ['tour'],
    data() {
        return {
            debug: false,
            activated: false,
            moduleName: 'Activities',
            booking_token: null,
            leadTraveller: {},
            activity: {},
            travellers: [],
            activities: [],
            allActivities: [],
            addons: [],
            selected: [],
            previous_activity_id: 0,
            showOptions: false,
            showAddon: 0,
            showUpgrade: 0,
            bookedActivities: []
        }
    },
    created() {
        let that = this
        bus.$on('setBookingToken', (bookingData) => {
            that.booking_token = bookingData
            that.debug && console.log(`>>>> ${that.moduleName} module: tour: ${that.tour.name}, booking ${that.booking_token}`)
            that.loadLeadTraveller(that.booking_token)
        })
        bus.$on("AdditionalTravelersLoaded", (travellers, init = false) => {
            that.debug > 2 && console.log(`>>>> ${that.moduleName} module: travellers loaded: ${travellers}`);
            travellers.map(traveller => that.travellers.push(traveller));
            that.debug > 6 && console.log('AdditionalTravellerLoaded Event: loading activityBooking for ', that.travellers)
            that.loadActivityBooking(that.booking_token)
        })
    },
    mounted() {
        this.loadActivityInventory()
    },
    methods: {
        loadLeadTraveller(token) {
            let that = this
            axios.get(`/api/booking/customer/${token}`)
                .then(response => {
                    that.debug > 4 && console.log('Activity GET LEAD', response.data.customer)
                    that.leadTraveller = response.data.customer
                    const loaded = that.travellers.filter(t => t.id == that.leadTraveller.id)
                    that.debug > 3 && console.log('loaded',loaded)
                    if (that.travellers.length < 1 || !loaded || loaded.length == 0) {
                        that.debug > 6 && console.log('unshifting ', that.leadTraveller.id)
                        that.travellers.unshift(that.leadTraveller)
                    }
                })
                .catch(error => console.log(error))
        },
        completeActivities() {
            let that = this
            const bookings = []
            console.log('activity booked summary')
            that.activities.map(a => {
                if (a.status == 'booked') {
                    bookings.push(a)
                }
            })
            console.log('addons booked summary')
            that.addons.map(a => {
                if (a.status == 'booked') {
                    bookings.push(a)
                }
            })
            bus.$emit('activityBooking', bookings)
            this.activated = false
        },
        bookActivityAddon(activity) {
            let that = this
            axios.post('/api/booking/activity/addon/book', {
                activity: activity,
                customers: this.travellers,
                token: this.booking_token
            })
            .then(response => {
                    const booking = response.data.booking
                    that.debug>3 && console.log('booking addon, booking', booking)
                    that.addons.map((a,i) => {
                        if (a.tour_component_type == 'Add-on' && a.activity_inventory_tour_id == booking[0].activity_inventory_tour_id) {
                            a.status = 'booked'
                            that.addons.splice(i,1,a)
                            that.debug> 5 && console.log('splicing in booked status',a)
                        }
                    })
            })
        },
        cancelActivityAddon(activity) {
            let that = this
            axios.post('/api/booking/activity/addon/cancel', {
                activity: activity,
                customers: this.travellers,
                token: this.booking_token
            })
            .then(response => {
                const booking = response.data.booking
                that.debug>3 && console.log('booking addon cancel, bookings', booking)
                that.addons.map((a,i) => {
                    if (a.tour_component_type == 'Add-on' 
                        && a.activity_inventory_tour_id == booking[0].activity_inventory_tour_id) {
                        a.status = ''
                        that.addons.splice(i,1,a)
                    }
                })
            })
            .catch(error => console.log(error))
        },
        bookActivityUpgrade(activity) {
            let that = this
            axios.post('/api/booking/activity/upgrade/book', { 
                activity: activity,
                customers: this.travellers,
                token: this.booking_token
            })
            .then(response => {
                if (response.data.success) {
                    const booking = response.data.booking
                    const baseId = response.data.base_id
                    that.activities.map((a,i) => {
                        if (a.tour_component_type == 'Upgrade' 
                        && a.activity_inventory_tour_id == booking[0].activity_inventory_tour_id) {
                            a.status = 'booked'
                            that.activities.splice(i,1,a)
                        }
                    })
                    that.activities.map((e,i) => {
                        if (e.activity_inventory_tour_id == baseId) {
                            e.status = 'upgraded'
                            that.activities.splice(i,1,e)
                        }
                    })
                }
            })
            .catch(error => console.log(error))
        },
        cancelActivityUpgrade(activity) {
            let that = this
            axios.post('/api/booking/activity/upgrade/cancel', {
                activity: activity,
                customers: this.travellers,
                token: this.booking_token
            })
            .then(response => {
                const booking = response.data.booking
                const baseId = response.data.base_id
                that.activities.map((a,i) => {
                    if (a.tour_component_type == 'Upgrade' 
                        && a.activity_inventory_tour_id == booking[0].activity_inventory_tour_id) {
                        a.status = "cancel"
                        that.activities.splice(i,1,a)
                    }
                })
                that.activities.map((e,i) => {
                    if (e.activity_inventory_tour_id == baseId) {
                        e.status = ''
                        that.activities.splice(i,1,e)
                    }
                })
            })
            .catch(error => console.log(error))
        },
        startDate(event) {
            return dates.bookingTime(event.starts_at)
        },
        endDate(event) {
            return dates.bookingTime(event.ends_at)
        },
        toggleActivities() {
            this.activated = !this.activated
        },
        loadActivityInventory() {
            let that = this
            axios.get(`/api/booking/activities/tour/${this.tour.id}`)
                .then(response => {
                    that.debug>2 && console.log('BookingFormActivity: Inventory response: ',response)
                    that.allActivities = response.data.activities
                    that.activities = that.allActivities.filter(a => a.tour_component_type != 'Add-on');
                    that.addons = that.allActivities.filter(a => a.tour_component_type == 'Add-on');
                    that.loadActivityBooking(that.booking_token)
                    if (that.debug>4) {
                        console.log('Loaded activities',that.activities)
                        console.log('Loaded addons',that.addons)
                    } 
                })
                .catch(error => {
                    console.log(error)
                })
        },
        loadActivityBooking(token) {
            let that = this
            axios.get(`/api/booking/activities/booking/${token}`)
                .then(response => {
                    //console.log('booking-activity response', response)
                    that.bookedActivities = response.data.bookings
                    that.debug > 4 && console.log('response', response)
                    that.debug > 2 && console.log('bookedActivities', that.bookedActivities)
                    that.debug > 3 && console.log('bookedmap', that.bookedActivities.map(b => b.tour_component_type))
                    
                    that.allActivities.map((a,i) => {
                        const isMatched2 = that.bookedActivities.map(b => b.activity_inventory_tour_id).includes(a.activity_inventory_tour_id)
                        const isMatched3 = that.bookedActivities.map(b => b.tour_component_type).includes(a.tour_component_type)
                        const isMatched = isMatched2 && isMatched3
                        that.debug > 6 && console.log('check settings', a, that.bookedActivities, isMatched2, isMatched3)
                        if (isMatched) {
                            that.allActivities[i].status = 'booked'
                        } else {
                            that.allActivities[i].status = ''
                        }
                        const base_id = that.bookedActivities.map(b => {
                            if (b.base_id == a.activity_inventory_tour_id) {
                                return b.base_id
                            }
                        })
                        if (base_id) {
                            that.debug > 6 && console.log('CHECK ACTIVIITES for BASEID',  that.bookedActivities)
                            that.debug > 6 && console.log('base_id', base_id, 'aitid', a.activity_inventory_tour_id)
                            that.activities.map(a => {
                                if (a.activity_inventory_tour_id == base_id) {
                                    a.status='upgraded'
                                }
                            })
                        }
                    })
                   that.debug > 2 && console.log('updated activitybookings', that.activities.map(a => a.status))
                })
                .catch(error => console.log(error))
        }
    }
}
</script>
<style scoped lang="scss">
    .btn.btn-large {
        margin-bottom: 1.5rem;
    }
    .btn.btn-small {
        margin-bottom: 1rem;
        padding: 0.5rem;
        width: 100%;
        display: block;
    }
    .options {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
    }
    .listing {
        font-weight: 300;
    }
    .listing.greyed {
        display: none;
    }
    .listing.emphasiseIt {
        font-weight: 700;
    }
    .blankIt {
        color: #000;
    }
    .divider {
        border-top: 2px black solid;
        margin-top: 1rem;
    }
    .controlBreak {
       border-top: 1px black solid;
    }
    .listing {
        display: flex;
        flex-direction: row;
    }
    .listing div {
        padding: 0.25rem;
        margin: 1rem;
    }
    .column-id {
        width: 0.5rem;
    }
    .column-name {
        width: 11rem;
}
    .column-description {
        width: 22rem;
    }
    .column-address, 
    .column-notes {
        width: 11rem;
    }
    .column-starts-at, 
    .column-ends-at {
        width: 8rem;
    }
    .column-ticket-type {
        width: 5rem;
    }
    .column-component-type {
        width: 5rem;
    }
    .column-component-note {
        width: 8rem;
    }
    @media screen and (max-width: 992px) {
        .column-id {
            display: none;
        }
        .listing:after {
            content: '';
            border-bottom: 1px grey solid;
        }
        .listing {
            flex-direction: column;
        }
        .listing div {
            margin: 0.25rem 0;
        }
        .listing.headings {
            display: none;
        }
    }
</style>
