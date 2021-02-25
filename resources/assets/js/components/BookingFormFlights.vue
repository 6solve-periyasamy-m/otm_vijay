<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-1">
                    <button class="btn btn-link cardhead" @click="toggleFlights">
                        Flights
                    </button>
                </h5>
            </div>
            <div class="card-body" v-if="showFlights">
                <h4> Flight out </h4>
                <div class="row">
                    <div class="col-sm-6">
                        <select v-model="flight_outward">
                            <option v-for="flight in flights.data" :key="flight.id" :value="flight.id">
                            {{flight.airline_name}} {{flight.flight_number}} {{flight.travel_class}} 
                            {{airports[flight.departure_airport_id].airport_name}} to {{airports[flight.arrival_airport_id].airport_name}} 
                            {{dmy(flight.departure_date_time)}} 
                            </option>
                        </select>
                    </div>
                </div>
                
                <button @click="addFlight">Add a flight</button>
                <div v-for="(flight_selected, c) in flights_selected" :key="flight_selected">
                    <booking-form-flight-selector 
                        :tour="tour" 
                        :airports="airports" 
                        :flights="flights" 
                        :types="tour_flight_types">
                    </booking-form-flight-selector>
                </div>

                <h4> Flight home </h4>
                <div class="row">
                    <div class="col-sm-6">
                        <select v-model="flight_home">
                            <option v-for="flight in flights.data" :key="flight.id" :value="flight.id">
                            {{flight.airline_name}} {{flight.flight_number}} {{flight.travel_class}}
                            {{airports[flight.departure_airport_id].airport_name}} to {{airports[flight.arrival_airport_id].airport_name}} 
                            {{dmy(flight.departure_date_time)}} 
                            </option>
                        </select>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
import dates from '../utilities'
import BookingFormFlightSelector from './BookingFormFlightSelector.vue'
export default {
  components: { BookingFormFlightSelector },
    props: ['tour'],
    data() {
        return {
            c: 0,
            showFlights: false,
            airports: [],
            airport: {},
            flightFromOptions: [],
            flight_from: {},
            flightToOptions: [],
            flight_to: {},
            flights: [],
            flights_selected: [],
            flight: 0,
            flight_outward: {},
            flight_home: {},
            tour_flight_types: ['Outward', 'Excursion', 'Intercity', 'Home'],

        }
    },
    mounted() {
        this.getFlights(this.tour.id) // need an event bus to set the tourId from the 
    },
    computed: {
        otherairports: function() {
            const airports = this.airports
            console.log('other .... ',airports)
            const items  = airports.filter((airport) => {
                return airport.airport_name != this.flight_from;
            })
            return items
        }
    },
    methods: {
        dmy(s) {
            console.log(s)
            return dates.makeDateFromString(s)
        },
        toggleFlights() {
            this.showFlights = !this.showFlights
        },
        getAirports() {
            axios.get('/api/booking/airports')
                .then(response => {
                    this.airports = response.data.airports
                    console.log('airports', this.airports)
                })
                .catch(error => console.log(error.message))
        },
        getFlights(tour) {
            axios.get(`/api/booking/flights/${tour}`)
                .then(response => {
                    this.flights = response.data.data
                    this.getAirports()
                })
                .catch(error => console.log(error.message))
        },
        addFlight() {
            let flight = this.flight++;
            this.flights_selected.push(flight)
        }
    },
}
</script>