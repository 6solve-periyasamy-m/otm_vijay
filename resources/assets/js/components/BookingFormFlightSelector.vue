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
                v-model="flightId">
                <option value="Flight Select" v-if="!selected_item" selected>Flight Select</option>
                <option 
                    v-for="flight in tour_flights_filtered" 
                    :key="flight.id" 
                    :value="flight.flight_inventory_tour_id"
                >
                    {{flightValue(flight)}}
                </option>
            </select>
        </div>
    </div>
</template>
<script>
import dates from '../utilities'
import { bus } from '../bus'
export default {
    props: [ 'traveller', 'tour', 'airports', 'flights', 'types', 'enabled', 'custom', 'selected_item'],
    data() {
        return {
            debug: false,
            flightId: '',
            tour_flight_type: '',
            flight_selected: '',
            tour_flight_type: {},
            tour_flight_types: [],
            tour_flights_filtered: [],
            tour_flights: '',
            flight: {}
        }
    },
    mounted() {
        let that = this
        bus.$on('debugOverride', (debug) => that.debug = debug)
        // bus.$on('setCustomFlightsForTraveller', function(customtraveller, selected) {
        //     console.log('&&&&&&******^^^^^ BFFS: EVENT ON setting customFlight for ', customtraveller, selected)
        // })
        this.debug && console.log('BFFS Mounted', this.traveller, this.tour, this.airports, this.flights, this.types, this.enabled, this.custom)
    },
    created() {
        this.tour_flights = this.flights
        this.tour_flight_types = this.types
        this.tour_airports = this.airports
        this.tour_flight_type = this.tour_flight_types[0].toLowerCase()
        this.flightId = this.selected_item
        this.filterFlights()
    },
    watch: {
        flightId: function(flight, oldFlight) {
            if (flight != oldFlight && flight != null) {
                this.debug>4 && console.log('BFFS ... EVENT EMIT flight-selected', `set_${this.tour_flight_type}`, 'flight set to ', flight, ' flight was ', oldFlight, ' tour:', this.tour, ' traveller:',this.traveller)
                if (oldFlight > 0) {
                    bus.$emit(`set_${this.tour_flight_type}`, flight, this.tour, this.traveller, this.custom)
                } else {
                    this.debug>2 && console.log("BFFS loaded, not a change so no event emitted")
                }
            } else {
                this.debug>6 && console.log('BFFS Flight was NULL, flightId watch fired but not flight was selected yet')
            }
        }
    },
    methods: {
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
            return `${dates.makeDateFromString(flight.departure_date_time)} ${flight.airline_name} ${flight.flight_number} ${flight.travel_class} From ${this.airports[flight.departure_airport_id].airport_name} To ${this.airports[flight.arrival_airport_id].airport_name}`
        },
        dmy(s) {
            return dates.makeDateFromString(s)
        },
    }
}
</script>
