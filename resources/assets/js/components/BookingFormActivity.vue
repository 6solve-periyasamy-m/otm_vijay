<template>
    <div class="container">
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
                    <div class="column-ticket-type">
                        Ticket type
                    </div>
                </div>
                <div class="listing" v-for="activity in activities" :key="activity.activity_inventory_tour_id">
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
                    <div class="column-ticket-type">
                        {{activity.ticket_type_name}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import dates from '../utilities'
import { bus } from '../bus'
import Vue from 'vue'
import axios from 'axios'
export default {
    props: ['tour'],
    data() {
        return {
            debug: false,
            activated: false,
            moduleName: 'Activities',
            booking_token: null,
            travellers: [],
            activities: []
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
        bus.$on("AdditionalTravelersLoaded", (travellers) => {
            this.debug>2 && console.log(`>>>> ${that.moduleName} module: travellers loaded: ${travellers}`);
            travellers.map(traveller => this.travellers.push(traveller));
            this.loadActivityBooking(this.travellers)
        })
    },
    computed: {
    },
    methods: {
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
                })
                .catch(error => {
                    console.log(error);
                })
        },
        loadActivityBooking() {
            this.debug>1 && console.log('loadActivityBooking')
        }
    }
}
</script>
<style scoped lang="scss">
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
        width: 12rem;
}
    .column-description {
        width: 24rem;
    }
    .column-address, 
    .column-notes {
        width: 12rem;
    }
    .column-starts-at, 
    .column-ends-at {
        width: 10rem;
    }
    .column-ticket-type {
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
