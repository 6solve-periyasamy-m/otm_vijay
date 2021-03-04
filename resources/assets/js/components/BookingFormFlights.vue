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
                <div class="row">
                    <div class="col-sm-12">
                            <h4> Flights </h4>
                            <booking-form-flight-selector 
                                :tour="tour" 
                                :airports="airports" 
                                :flights="outbound_flights" 
                                :types="outbound_type">
                            </booking-form-flight-selector>
                       
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                            <booking-form-flight-selector 
                                :tour="tour" 
                                :airports="airports" 
                                :flights="inbound_flights" 
                                :types="inbound_type">
                            </booking-form-flight-selector>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <h4> Add On Flights </h4>
                        <!-- button @click="addFlight">Add a flight</button -->
                        <div v-for="flight in other_flights" :key="flight.id">
                            <booking-form-flight-selector v-model="flight_selected"
                                :tour="tour" 
                                :airports="airports" 
                                :flights="flights" 
                                :types="tour_flight_optional_types">
                            </booking-form-flight-selector>
                        </div>
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
            outbound_flights: [],
            inbound_flights: [],
            other_flights: [],
            flights_selected: [],
            flight_selected: {},
            flight: 0,
            flight_outward: {},
            flight_home: {},
            outbound_type: ['Outbound'],
            inbound_type: ['Inbound'],
            tour_flight_types: ['Outbound', 'Inbound'],
            tour_flight_optional_types: ['Excursion', 'Connection'],
            debug: 2
        }
    },
    async mounted() {
        await this.getFlights(this.tour.id)
        this.outbound_flights = this.flights.filter((flight) => flight.flight_type == 'Outbound')
        this.inbound_flights = this.flights.filter((flight) => flight.flight_type == 'Inbound')
        this.other_flights = this.flights.filter((flight) => flight.flight_type != 'Outbound' && flight.flight_type != 'Inbound')
        console.log('all flights', this.flights.map((f) => {
            return f.flight_type
        }))
        console.log('flights (other)', this.other_flights)
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
        addFlight() {
console.log('flight selected', this.flight_selected)
        },
        dmy(s) {
            console.log(s)
            return dates.makeDateFromString(s)
        },
        toggleFlights() {
            this.showFlights = !this.showFlights
        },
        async getAirports() {
            var that = this
            await axios.get('/api/booking/airports')
                .then(response => {
                    that.airports = response.data.airports
                })
                .catch(error => console.log(error.message))
        },
        async getFlights(tour) {
            var that = this
            await axios.get(`/api/booking/flights/${tour}`)
                .then(response => {
                    that.flights = response.data.data
                    that.getAirports()
                })
                .catch(error => console.log(error.message))
        },
        async getFlightType(tour, type = '') {
            var that = this
            await axios.get(`/api/booking/flights/${tour}/${type}`)
                .then(response => {
                    that.flights = response.data.data
                    that.getAirports()
                })
                .catch(error => console.log(error.message))
        },
        addOutboundFlight(flight) {
            this.outbound_flights.push(flight)
        },
        addInboundFlight(flight) {
            this.inbound_flights.push(flight)
        }
    }
}
</script>