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
                <div class="row" v-if="showOtherFlights">
                    <div class="col-sm-9">
                        <h4> Add On Flights </h4>
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
                <div v-for="traveller as booking.additionalTravellers">
                    <div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import dates from '../utilities'
import BookingFormFlightSelector from './BookingFormFlightSelector.vue'

/**
 * Flights selection component
 * Loads in available flights for this tour
 * asks the lead booker to select from Outbound and Inbound flights
 * and to select addons for each kind that is available
 * Any selected flights can then be booked or edited.
 * 
 * Known Issues:
 * 1. Addon selector: if we are building an itinery: the order of these selections matters 
 * - the dates should set the order in the order summary
 * Expansions:
 * 1. Tour members may not all book same addons
 * 2. Tour members may require different Outbound/Inbound selections
 */
export default {
    components: { BookingFormFlightSelector },
    props: ['tour'],
    data() {
        return {
            outbound_flights: [],
            inbound_flights: [],
            other_flights: [],
            airports: [],
            flights: [],
            showFlights: false,
            showOtherFlights: false,
            outbound_type: ['Outbound'],
            inbound_type: ['Inbound'],
        }
    },
    async mounted() {
        await this.getFlights(this.tour.id)
        console.log(this.flights)
        this.outbound_flights = this.flights.filter((flight) => flight.flight_type == 'Outbound')
        this.inbound_flights = this.flights.filter((flight) => flight.flight_type == 'Inbound')
        this.other_flights = this.flights.filter((flight) => flight.flight_type != 'Outbound' && flight.flight_type != 'Inbound')
    },
    computed: {
        otherairports: function() {
            const airports = this.airports
            const items  = airports.filter((airport) => {
                return airport.airport_name != this.flight_from;
            })
            return items
        }
    },
    methods: {
        dmy(s) {
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
            console.log('getflights for tour ', tour)
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
