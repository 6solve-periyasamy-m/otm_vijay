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
                <div class="listing" v-for="(activity,index) in activities" 
                    :key="activity.activity_inventory_tour_id" 
                    :class="{controlBreak : activity.activity_id !== previous_activity_id}"
                    v-if="activity.tour_component_type != 'Add-on'">

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
                        <button class="btn btn-primary btn-small" v-if="!isBooked(activity) && activity.tour_component_type === 'Upgrade' && activity.stock" @click="bookActivity(activity)">Book</button>
                        <button class="btn btn-primary btn-small" v-if="isBooked(activity) && activity.tour_component_type === 'Upgrade'" @click="bookActivity(activity)">Cancel</button>
                    </div>
                    <div v-show="false">{{ previous_activity_id = activity.activity_id }}</div>
                    <!-- {{index}} {{selected}} {{activity.activity_inventory_tour_id}} {{selected[activity.activity_inventory_tour_id]}} -->
                </div>
                <div class="divider"><h5>Optional Additional activities</h5></div>
                <div class="listing" v-for="(activity,index) in activities" 
                    :key="activity.activity_inventory_tour_id" 
                    :class="{controlBreak : activity.activity_id !== previous_activity_id}"
                    v-show="activity.tour_component_type == 'Add-on'">
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
                        <button class="btn btn-primary btn-small" v-if="activity.tour_component_type === 'Upgrade'" @click="bookActivity(activity)">Book</button>
                        <button class="btn btn-primary btn-small" v-if="activity.tour_component_type === 'Add-on'" @click="bookActivity(activity)">Add on</button>
                        <button class="btn btn-primary btn-small" v-if="activity" @click="bookActivity">Cancel</button>
                    </div>
                    <div v-show="false">{{ previous_activity_id = activity.activity_id }}</div>
                    <!-- {{index}} {{selected}} {{activity.activity_inventory_tour_id}} {{selected[activity.activity_inventory_tour_id]}} -->
                </div>
                <pre>{{activities}}</pre>

                <div>
                    <button class="btn btn-primary" @click="bookActivity">Book Selected</button>
                </div>
               
            </div>
        </div>
    </div>
</template>
<script>
import dates from '../utilities'
import { bus } from '../bus'
import axios from 'axios'
export default {
    props: ['tour'],
    data() {
        return {
            debug: 9,
            activated: false,
            moduleName: 'Activities',
            booking_token: null,
            travellers: [],
            activities: [],
            selected: [],
            previous_activity_id: 0,
            showOptions: false,
            showAddon: 0,
            showUpgrade: 0
        }
    },
    async mounted() {
        this.loadActivityInventory()
    },
    created() {
        let that = this
        bus.$on('setBookingToken', (bookingData) => {
            that.booking_token = bookingData
            that.debug && console.log(`>>>> ${that.moduleName} module: tour: ${that.tour.name}, booking ${that.booking_token}`)
        })
        bus.$on('customerLoaded', (leadTraveller) => {
            this.leadTraveller = leadTraveller
        })
        bus.$on("AdditionalTravelersLoaded", (travellers, init = false) => {
            this.debug>2 && console.log(`>>>> ${that.moduleName} module: travellers loaded: ${travellers}`);
            travellers.map(traveller => this.travellers.push(traveller));
            this.loadActivityBooking(this.travellers)
        })
    },
    computed: {
    },
    methods: {
        // upgrades are either booked or not
        // simple version is to query the database
        isBooked(activity) {
            const booked = activity.status == 'booked'
            return booked
        },
        included(id) {
            console.log(this.activities)
            if (this.activities[id].tour_component_type == 'Included') {
                return 'checked'
            }
            return ''
        },
        bookActivity(activity) {
            console.log('Booking Activities for id ', activity)
            axios.post('/api/booking/activity/book/', activity)
                .then(response => {
                    console.log('booked', response)
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
                    that.activities = response.data.activities
                    that.loadBookingActivities(that.booking_token);
                    //that.loadBookingStatus(that.booking_token)
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
        loadBookingActivities(token) {
            this.debug>1 && console.log('loadActivityBooking')
            axios.get(`/api/booking/activities/booking/${token}`)
                .then(response => {
                    console.log('booking-activity response', response)
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
        visibility: hidden;
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
