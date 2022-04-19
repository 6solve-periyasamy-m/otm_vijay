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
                    <div class="column-starts-at">
                        {{startDate(activity)}}
                        <br>to
                        <br>{{endDate(activity)}}
                    </div>
                    <div class="column-name" :class="{blankIt: activity.tour_component_type == 'Upgrade'}">
                        {{ activity.name }}<br>{{ activity.description !== activity.name ? activity.description :''}} 
                    </div>
                    <div class="column-address" :class="{blankIt: activity.tour_component_type == 'Upgrade'}">
                        {{ activity.address != undefined && activity.address.length ? activity.address : '-'}}
                        <br>
                        {{ activity.address_region != undefined && activity.address_region.length ? activity.address_region : ''}}
                    </div>
                    <div class="column-component-type emphasiseIt">
                        {{activity.tour_component_type}}
                    </div>
                    <div class="column-ticket-type" :class="{emphasiseIt: activity.tour_component_type == 'Upgrade'}">
                        {{activity.ticket_type_name =='Basic' ? '' : activity.ticket_type_name}}
                    </div>
                    <div class="column-stock" v-if="activity.stock < 1">
                        <h3 style="color: red">OUT OF STOCK</h3>
                    </div>
                    <div class="column-action">
                        <button
                            class="btn btn-primary btn-small" 
                            v-if="activity.status=='booked' && activity.tour_component_type === 'Upgrade'" 
                            @click="cancelActivityUpgrade(activity)">Cancel</button>
                        <button
                            v-if="activity.status!='booked' && activity.tour_component_type === 'Upgrade' && activity.stock" 
                            class="btn btn-primary btn-small" 
                            @click="bookActivityUpgrade(activity)">Book</button>
                    </div>                    <!-- {{index}} {{selected}} {{activity.activity_inventory_tour_id}} {{selected[activity.activity_inventory_tour_id]}} -->
                </div>
                <div class="divider"><h5>Optional Additional activities</h5></div>
                <div class="listing" v-for="(activity,index) in addons" 
                    :key="activity.activity_inventory_tour_id">
                    <div class="column-starts-at">
                        {{startDate(activity)}}
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
                    <div class="column-action">
                        <button class="btn btn-primary btn-small" v-if="activity.tour_component_type === 'Add-on'" @click="bookActivityAddon(activity)">Add on</button>
                        <button class="btn btn-primary btn-small" v-if="activity" @click="cancelActivityAddon(activity)">Cancel</button>
                    </div>
                    <!-- {{index}} {{selected}} {{activity.activity_inventory_tour_id}} {{selected[activity.activity_inventory_tour_id]}} -->
                </div>
                <div>
                    <button class="btn btn-primary" @click="bookActivities">Book Selected</button>
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
            debug: 9,
            activated: false,
            moduleName: 'Activities',
            booking_token: null,
            leadTraveller: {},
            activity: {},
            travellers: [],
            activities: [],
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
        // bus.$on('customerLoaded', (leadTraveller) => {
        //     that.leadTraveller = leadTraveller
        // })
        bus.$on("AdditionalTravelersLoaded", (travellers, init = false) => {
            that.debug>2 && console.log(`>>>> ${that.moduleName} module: travellers loaded: ${travellers}`);
            travellers.map(traveller => that.travellers.push(traveller));
            console.log('AdditionalTravellerLoaded Event: loading activityBooking for ', that.travellers)
            that.loadActivityBooking(that.travellers)
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
                    console.log('Activity GET LEAD', response.data.customer)
                    that.leadTraveller = response.data.customer
                    const loaded = that.travellers.filter(t => t.id == that.leadTraveller.id)
                    console.log('loaded',loaded)
                    if (that.travellers.length < 1 || !loaded || loaded.length == 0) {
                        console.log('unshifting ', that.leadTraveller.id)
                        that.travellers.unshift(that.leadTraveller)
                    }
                })
                .catch(error => console.log(error))
        },
        // upgrades are either booked or not
        // simple version is to query the database
        isBooked(activity) {
            console.log('isBooked', activity)
            const status = activity.status 
            const bookingStatus = status == 'booked'
            return bookingStatus
        },
        checkBooked(customer_id, booking_id) {
            axios.get(`/api/booking/activity/find/booking/${booking_id}/${customer_id}`)
            .then(response => {
                console.log(response)
            })
            .catch(error => {
                console.log(error)
            })
        },
        included(id) {
            console.log(this.activities)
            if (this.activities[id].tour_component_type == 'Included') {
                return 'checked'
            }
            return ''
        },
        bookActivities() {
            console.log('book activities')
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
                    const activity = that.activities.filter(a => a.activity_inventory_tour_id === booking[0].activity_inventory_tour_id && a.tour_component_type === 'Upgrade')
                    const allActivities = that.activities
                    allActivities.map((a,i) => {
                        if (a.tour_component_type == 'Upgrade' && a.activity_inventory_tour_id == booking[0].activity_inventory_tour_id) {
                            a.status = 'booked'
                            that.activities.splice(i,1,a)
                        }
                    })
                    allActivities.map((e,i) => {
                        if (e.activity_inventory_tour_id == baseId) {
                            e.status = 'upgraded'
                            that.activities.splice(i,1,a)
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
                const allActivities = that.activities
                const booking = response.data.booking
                const baseId = response.data.base_id
                allActivities.map((a,i) => {
                    if (a.tour_component_type == 'Upgrade' 
                        && a.activity_inventory_tour_id == booking[0].activity_inventory_tour_id) {
                        a.status = "cancel"
                        that.activities.splice(i,1,a)
                        console.log('>>> cancel status:', i, that.activities[i], that.activities)
                    }
                })
                allActivities.map((e,i) => {
                    if (e.activity_inventory_tour_id == baseId) {
                        e.status = ''
                        that.activities.splice(i,1,a)
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
                    that.debug>3 && console.log('BookingFormActivity: Inventory: ',response)
                    that.activities = response.data.activities.filter(a => a.tour_component_type != 'Add-on');
                    that.addons = response.data.activities.filter(a => a.tour_component_type == 'Add-on');
                    that.loadActivityBooking(that.booking_token)
                    //that.loadBookingStatus(that.booking_token)
                    console.log('activity inventory loaded, now loading bookings',that.activities)
                    
                })
                .catch(error => {
                    console.log(error)
                })
        },
        // // load in an array of activity booking states: ie: booked: true/false
        // loadBookingStatus(token) {
        //     axios.get(`/api/booking/activity/booking/status/${token}` )
        //         .then(response => {
        //             console.log('booking activity response', response)
        //         })
        //         .catch(error => console.error(error))
        // },
        // Looks wrong: when a traveller is added: we need to load activity books for them??
        loadActivityBooking(token) {
            let that = this
            this.debug>1 && console.log('loadActivityBooking')
            axios.get(`/api/booking/activities/booking/${token}`)
                .then(response => {
                    //console.log('booking-activity response', response)
                    that.bookedActivities = response.data.bookings
                    console.log('parsed bookings', that.bookedActivities)
                    console.log('bookedmap', that.bookedActivities.map(b => b.tour_component_type))
                    that.activities.map((a,i) => {
                        const isMatched2 = that.bookedActivities.map(b => b.activity_inventory_tour_id).includes(a.activity_inventory_tour_id)
                        const isMatched3 = that.bookedActivities.map(b => b.tour_component_type).includes(a.tour_component_type)
                        const isMatched = isMatched2 && isMatched3
                        console.log(a, that.bookedActivities, isMatched2, isMatched3)
                        if (isMatched) {
                            that.activities[i].status = 'booked'
                        } else {
                            that.activities[i].status = ''
                        }
                        const base_id = that.bookedActivities.map(b => {
                            if (b.base_id == a.activity_inventory_tour_id) {
                                return b.base_id
                            }
                        })
                       
                        if (base_id) {
                            console.log('CHECK ACTIVIITES for BASEID',  that.bookedActivities)
                            console.log('base_id', base_id, 'aitid', a.activity_inventory_tour_id)
                            that.activities.map(a => {
                                if (a.activity_inventory_tour_id == base_id) {
                                    a.status='upgraded'
                                }
                            })
                        }
                    })
                    console.log('updated activitybookings', that.activities.map(a => a.status))
                })
                .catch(error => console.log(error))
        }
    }
}
</script>
<style scoped lang="scss">
    .options {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
    }
    .emphasiseIt {
        font-weight: bold;
    }
    .blankIt {
        color: grey;
    }
    .listing.greyed {
        display: none;
        color: #ccc;
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
