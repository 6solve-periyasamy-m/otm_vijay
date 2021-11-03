<template>
    <div class="row compress">
        <div v-if="tour_flight_types.length > 1" class="col-sm-2">
            <select 
                v-model="tour_flight_type" 
                @change="filterFlights">
                <option selected disabled value="">Select</option>
                <option v-for="tour_flight_type in tour_flight_types" :key="tour_flight_type" :value="tour_flight_type">
                   {{tour_flight_type}}
                </option>
            </select>
        </div>
        <div v-else class="col-sm-2">
            {{tour_flight_types[0]}} 
        </div>
        <div class="col-sm-10" v-if="tour_flights_filtered">
            <select 
                :disabled="!enabled"
                @change="changeFlight"
                v-model="flightId">
                <option value="0" v-if="!flight_selected" selected>{{caption}}</option>
                <option 
                    v-for="flight in tour_flights_filtered" 
                    :key="flight.id" 
                    :value="flight.flight_inventory_tour_id"
                >
                    {{flightValue(flight)}}
                </option>
            </select>
            <button v-if="custom && flight_selected" class="delete" @click="removeBooking(flightId, tour_flight_type)">Reset</button>
        </div>
    </div>
</template>
<script>
import dates from '../utilities'
import { bus } from '../bus'
export default {
    props: [ 'traveller', 'tour', 'airports', 'flights', 'types', 'enabled', 'custom', 'selected_item','token'],
    data() {
        return {
            debug: false,
            flightId: '',
            tour_flight_type: '',
            flight_selected: this.selected_item,
            tour_flight_type: {},
            tour_flight_types: [],
            tour_flights_filtered: [],
            tour_flights: '',
            flight: {},
            caption: 'Flight Select'
        }
    },
    mounted() {
        let that = this
        bus.$on('debugOverride', (debug) => that.debug = debug)
        console.log('BFFS: this.selected_item', this.selected_item)
        this.debug && console.log('BFFS Mounted', this.traveller, this.tour, this.airports, this.flights, this.types, this.enabled, this.custom, this.token)
    },
    created() {
        let that = this
        this.identification = this.token
        this.tour_flights = this.flights
        this.tour_flight_types = this.types
        this.tour_airports = this.airports
        this.tour_flight_type = this.tour_flight_types[0].toLowerCase()
        // BUG: this.selected_item is NULL on addons load?
        if (this.selected_item) {
            this.flightId = this.selected_item
            console.log('BFFS: setting flight id to prop ',this.selected_item)
            bus.$on('flightRemoved', (customer_detail_id) => {
                console.log('BFFS: flight to remove', customer_detail_id)
                this.flight_selected = 0
                console.log(that.tour_flights_filtered)
            })
        } else {
            console.log('WARNING: selected_item not set?')
        }
        this.filterFlights()
        bus.$on('setCustomFlightsForTraveller', function(customtraveller, selected) {
            console.log('BFFS EVENT ON setCustomFlightForTraveller ... setting customFlight for ', customtraveller, selected, that.flightId)
        })
         
    },
    methods: {
        changeFlight() {
            if (typeof this.flightId != 'undefined' && this.flightId != null && this.flightId != 0) {
                this.caption = 'Remove selection'
                this.debug>4 && console.log(`emit set_${this.tour_flight_type}`,this.flightId, this.tour, this.traveller, this.custom, this.token)
                bus.$emit(`set_${this.tour_flight_type}`, this.flightId, this.tour, this.traveller, this.custom, this.token)
            } else {
                this.caption = 'You can select a custom flight'
            }
        },
        removeBooking(booking, flight_type) {
            // props: [ 'traveller', 'tour', 'airports', 'flights', 'types', 'enabled', 'custom', 'selected_item'],
            // /booking/flights/remove/flight/{order_id]/{order_customer_id}/{type}/{custom}/{inventory_tour_id}
            this.caption = 'Reselect'
            this.debug>4 && console.log('removing', booking, flight_type, this.tour)
            bus.$emit('removeBooking', booking, flight_type, this.traveller);
        },
        filterFlights() {
            var that = this
            this.tour_flights_filtered = this.tour_flights.filter((flight) => {
                return flight.flight_type.toLowerCase() == that.tour_flight_type.toLowerCase()
            })
            this.debug>4 && console.log('tour flights filtered', this.tour_flights_filtered)
        },
        flightValue(flight) {
            if (typeof flight == 'undefined') {
                alert('not a flight?', flight)
                return ''
            }
            // TODO: Nic, can you verify if 'airline_name' needs to be renamed?
            return `${dates.makeDateFromString(flight.departs_at)} ${flight.airline_name} ${flight.flight_number} ${flight.travel_class} From ${this.airports[flight.departure_airport_id].name} To ${this.airports[flight.arrival_airport_id].name}`
        },
        dmy(s) {
            return dates.makeDateFromString(s)
        },
    }
}
</script>
