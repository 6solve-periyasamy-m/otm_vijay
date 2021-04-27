<template>
    <div class="row compress">
        <div v-if="tour_flight_types.length > 1" class="col-sm-2">
            <select 
                v-model="tour_flight_type" 
                @change="filterFlights">
                <option selected disabled value="">Select</option>
                <option v-for="(tour_flight_type) in tour_flight_types" :key="tour_flight_type" :value="tour_flight_type">
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
                <option selected value="">Selection</option>
                <option v-for="flight in tour_flights_filtered" :key="flight.id" :value="flight.flight_inventory_tour_id">
                    {{flightValue(flight)}}
                </option>
            </select>
        </div>
    </div>
</template>
<script>
import dates from '../utilities'
import { bus, booking } from '../bus'
export default {
    props: [ 'traveller', 'tour', 'airports', 'flights', 'types', 'enabled','custom'],
    mounted() {
        console.log('BFFS', [ this.traveller, this.tour, this.airports, this.flights, this.types, this.enabled, this.custom])
    },
    created() {
        this.tour_flights = this.flights
        this.tour_flight_types = this.types
        this.tour_airports = this.airports
        this.tour_flight_type = this.tour_flight_types[0].toLowerCase()
        this.filterFlights()
    },
    watch: {
        flightId: function(flight) {
            bus.$emit(`set_${this.tour_flight_type}`, flight, this.tour, this.traveller, this.custom)
            console.log('BFFS ... EVENT EMIT flight-selected', `set_${this.tour_flight_type}`, flight, this.tour, this.traveller)
        }
    },
    data() {
        return {
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
    methods: {
        filterFlights() {
            var that = this
            this.tour_flights_filtered = this.tour_flights.filter((flight) => {
                return flight.flight_type.toLowerCase() == that.tour_flight_type.toLowerCase()
            })
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
