<template>
    <div class="row">
            <select v-model="tour_flight_type">
                <option selected disabled value="">Select</option>
                <option v-for="tour_flight_type in types" :key="tour_flight_type" :value="tour_flight_type">
                    {{tour_flight_type}}
                </option>
            </select>
        <div class="col-sm-6">
            <select v-model="formId">
                <option selected disabled>Select</option>
                <option v-for="flight in flights" :key="flight.id" :value="flight.id">
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
    },
    data() {
        return {
            tour_flight_type: '',
            flight_selected: '',
        }
    },
    methods: {
        flightValue(flight) {
            return `${dates.makeDateFromString(flight.departure_date_time)} ${flight.airline_name} ${flight.flight_number} ${flight.travel_class} From ${this.airports[flight.departure_airport_id].airport_name} To ${this.airports[flight.arrival_airport_id].airport_name}`
        },
        dmy(s) {
            return dates.makeDateFromString(s)
        },
    }
}
</script>
