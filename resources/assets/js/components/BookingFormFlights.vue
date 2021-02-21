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
                <h4> Flight information </h4>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label">Flying from       
                        <select v-model="flight_from">
                                <option default value="">Select</option>
                                <option v-for="airport in airports" :key="airport.id">
                                    {{ airport.airport_name }}
                                </option>
                            </select>
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Flying to 
                            <select v-model="flight_to">
                                <option default value="">Select</option>
                                <option v-for="airport in otherairports" :key="airport.id">
                                    {{ airport.airport_name }}
                                </option>
                            </select>
                        </label>
                    </div>
                </div>
                {{flights}}
                <div class="row" v-if="flight_from && flight_to">
                    <h3>Available flights</h3>
                    <select v-model="flight_selected">
                        <option v-for="flight in flights" :key="flight.id">
                            {{flight.info}}
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            showFlights: false,
            airports: [],
            airport: {},
            flightFromOptions: [],
            flight_from: {},
            flightToOptions: [],
            flight_to: {},
            flights: [],
            flight_selected: {}
        }
    },
    mounted() {
        this.getAirports()
        this.getFlights(1) // need an event bus to set the tourId from the 
    },
    computed: {
        otherairports: function() {
            const items  = this.airports.filter((airport) => {
                if (airport.airport_name === this.flight_from) {
                    return false
                }
                return true
            })
            return items
        }
    },
    methods: {
        toggleFlights() {
            this.showFlights = !this.showFlights
        },
        getAirports() {
            axios.get('api/booking/airports')
                .then(response => (this.airports = response.data.airports))
                .catch(error => console.log(error.message))
        },
        getFlights(tour) {
            axios.get(`api/booking/flights/${tour}`)
                .then(response => {
                    this.flights = response.data.flights
                    console.log(response)
                })
                .catch(error => console.log(error.message))
        }
    },
}
</script>