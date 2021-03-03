<template>
    <div class="row">
        <div v-if="tour_flight_types.length > 1" class="col-sm-3 pull-right">
            <select v-model="tour_flight_type" @change="filterFlights">
                <option selected disabled value="">Select</option>
                <option v-for="(tour_flight_type) in tour_flight_types" :key="tour_flight_type" :value="tour_flight_type">
                   {{tour_flight_type}}
                </option>
            </select>
        </div>
        <div v-else class="col-sm-3 pullright">
            {{tour_flight_types[0]}}
        </div>
        <div class="col-sm-6" v-if="tour_flights_filtered">
            <select v-model="formId">
                <option selected disabled value="">Select</option>
                <option v-for="flight in tour_flights_filtered" :key="flight.id" :value="flight.id">
                {{flightValue(flight)}}
                </option>
            </select>
        </div>
    </div>
</template>
<script>
import dates from '../utilities';
export default {
    props: [ 'tour', 'airports', 'flights', 'types', 'formId'],
    mounted() {
        this.tour_flights = this.flights
        this.tour_flight_types = this.types
        this.tour_airports = this.airports

        console.log('ffs tour_flights', this.tour_flights)
        console.log('ffs tour_airports', this.tour_airports)
        console.log('ffs tour_flight_types', this.tour_flight_types)
        if (this.tour_flight_types.length == 1) {
            this.tour_flights_filtered = this.tour_flights;
        }
    },
    data() {
        return {
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
            this.tour_flights_filtered = [];
console.log('selected:', this.tour_flight_type)
        },
        flightValue(flight) {
            if (typeof flight == 'undefined') {
                alert('not a flight?', flight)
                return ''
            }
console.log('check flightValue', flight)
            return `${dates.makeDateFromString(flight.departure_date_time)} ${flight.airline_name} ${flight.flight_number} ${flight.travel_class} From ${this.airports[flight.departure_airport_id].airport_name} To ${this.airports[flight.arrival_airport_id].airport_name}`
        },
        dmy(s) {
console.log('datestring', s)
            return dates.makeDateFromString(s)
        },
    }
}
</script>
