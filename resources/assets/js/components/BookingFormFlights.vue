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
            <div class="card-body compress" v-if="showFlights">
                <div class="row">
                    <div class="col-sm-12">
                            <h4> Group Flight </h4><p>Flights for each member, unless custom selections made</p>
                            <booking-form-flight-selector 
                                v-model="selected_outbound"
                                :enabled="!travellerFlightOptions.includes(true)"
                                :custom="false"
                                :tour="tour" 
                                :traveller="leadTraveller"
                                :airports="airports" 
                                :flights="outbound_flights" 
                                :types="outbound_type"
                                :selected_item="selected_outbound_flight"
                            >
                            </booking-form-flight-selector>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                            <booking-form-flight-selector 
                                v-model="selected_inbound"
                                :enabled="!travellerFlightOptions.includes(true)"
                                :custom="false"
                                :tour="tour" 
                                :traveller="leadTraveller"
                                :airports="airports" 
                                :flights="inbound_flights" 
                                :types="inbound_type"
                                :selected_item="selected_inbound_flight"
                            >
                            </booking-form-flight-selector>
                    </div>
                </div>
                <div class="row" v-if="showOtherFlights">
                    <div class="col-sm-9">
                        <h4> Add On Flights </h4>
                        <div v-for="flight in other_flights" :key="flight.id">
                            <booking-form-flight-selector 
                                v-model="other_flight_selected"
                                :tour="tour" 
                                :airports="airports" 
                                :flights="flights" 
                                :types="tour_flight_optional_types">
                            </booking-form-flight-selector>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <button class="btn btn-primary" 
                            :disabled="checkCustomButton"
                            @click="customFlights()">
                            Customise
                        </button>
                    </div>
                </div>
                <div class="flight-customise" v-if="showCustomFlights">

                    <div class="row">
                        <div class="col-sm-3">
                            Custom Flights
                        </div>
                        <div class="col-sm-3">
                            Traveller
                        </div>
                        <div class="col-sm-3">
                            First name
                        </div>
                        <div class="col-sm-3">
                            Last name
                        </div>
                    </div>
                    <hr class="light" />
                    <div v-for="traveller in travellers" v-bind:key="traveller.order_customer_id">
                        <div class="row">
                            <div class="col-sm-3">
                                <input class="`customer-flight-${traveller.id}`" type="checkbox" name="custom" @change="flightOptionsCustomer(traveller.order_customer_id)">
                            </div>
                            <div class="col-sm-3">
                                {{ traveller.order_customer_id }} {{ traveller.is_lead_booker ? 'Lead' : 'Additional'}}
                            </div>
                            <div class="col-sm-3">
                                {{ traveller.first_name }}
                            </div>
                            <div class="col-sm-3">
                                {{ traveller.last_name}}
                            </div>
                            <div class="flight-options" v-if="travellerFlightOptions[traveller.order_customer_id]">
                                <h5>Flight Options for traveller</h5>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <booking-form-flight-selector
                                            v-model="custom_outbound"
                                            :enabled="true"
                                            :custom="true"
                                            :tour="tour"
                                            :traveller="traveller"
                                            :airports="airports" 
                                            :flights="unselected_outbound"
                                            :types="outbound_type"
                                            :selected_item="traveller.selected_inbound_addon">
                                        </booking-form-flight-selector>
                                        <booking-form-flight-selector 
                                            v-model="custom_inbound"
                                            :enabled="true"
                                            :custom="true"
                                            :tour="tour" 
                                            :traveller="traveller"
                                            :airports="airports" 
                                            :flights="unselected_inbound" 
                                            :types="inbound_type"
                                            :selected_item="traveller.selected_inbound_addon">
                                        </booking-form-flight-selector>
                                    </div>
                                </div>
                                <div class="row" v-if="showOtherFlights">
                                    <div class="col-sm-9">
                                        <h4> Add On Flights </h4>
                                        <div v-for="flight in other_flights" :key="flight.id">
                                            <booking-form-flight-selector 
                                                v-model="flight_selected"
                                                :tour="tour" 
                                                :traveller="traveller"
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
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import dates from '../utilities'
import BookingFormFlightSelector from './BookingFormFlightSelector.vue'
import { bus } from '../bus'
import Vue from 'vue'
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
    props: ['tour', 'order_id', 'order_token'],
    data() {
        return {
            debug: 5,

            token: null,
            airports: [],
            flights: [],
            travellers: [],

            outbound_flights: [],
            inbound_flights: [],
            other_flights: [],

            // group flight selections
            outbound_flight_selected: {},
            inbound_flight_selected: {},

            // flights displays ON/OFF
            showFlights: false,
            showCustomFlights: false,

            // otherflights feature is turned OFF (probably deprecate - it was for connections)
            showOtherFlights: false,

            // labels
            outbound_type: ['Outbound'],
            inbound_type: ['Inbound'],

            // for updating backend with a flight/tour/traveller selection
            set_outbound_flight: null,
            set_inbound_flight: null,
            flight_tour: {},
            flight_traveller: {},

            // for control of custom flight selection
            selected_outbound: null,
            selected_inbound: null,
            custom_outbound: null,
            custom_inbound: null,
            unselected_outbound: [],
            unselected_inbound: [],
            travellerFlightOptions: [],
            leadTraveller: null,
            selected_outbound_flight: null,
            selected_inbound_flight: null,
            outbound_group_order: {},
            inbound_group_order: {}
        }
    },
    async mounted() {
        let that = this

        this.token = this.order_token
        console.log('token has been set to ', this.token)
        bus.$on('debugOverride', (debug) => that.debug = debug)
        bus.$on('customerLoaded', (leadTraveller => that.leadTraveller = leadTraveller))
        bus.$on('setOrderToken', (token) => {
            console.log('>>> BFFlights order token detected ', token)
            that.token = token
        })
        await this.getFlights(this.tour.id)
        await this.loadFlights(this.order_id)
        this.outbound_flights = this.flights.filter((flight) => flight.flight_type == 'Outbound')
        this.inbound_flights = this.flights.filter((flight) => flight.flight_type == 'Inbound')
        this.other_flights = this.flights.filter((flight) => flight.flight_type != 'Outbound' && flight.flight_type != 'Inbound')
        this.debug && console.log('BookingFormFlights component mounted with flights: ', this.flights, ' for tour ', this.tour, ' using order ID ', this.order_id)
        console.log('MOUNTED: outbound flights', this.outbound_flights)
        console.log('MOUNTED: inbound flights', this.inbound_flights)
        // TODO: get customer_order_details for type='flight' customer_order_id = this.order_id
        //       select the booked flights in this.outbound_flights, this.inbound_flights
        //       if this is a confirmed order, 
        //          lock the flights for group
        //          if custom flights are changed, 
        //              add transactions to cancel previous booking and rebook
    },
    created() {
        // TODO: flight does not look like the right object??
        bus.$on('set_outbound', (flight_inventory_tour_id, flight_tour, traveller, custom) => {
            let that = this
            console.log('BFF set_outbound event: ', flight_inventory_tour_id, flight_tour, traveller, custom)
            if (traveller == null) {
                console.log('set_outbound: no traveller is passed in')
            } else if (this.leadTraveller == null) {
                console.log('set_outbound: leadTravller NOT set')
            } else if (traveller.id == this.leadTraveller.id && traveller.is_lead_booker && !custom) {
                this.unselected_outbound = this.outbound_flights.filter(flight => {
                    console.log('unselected outbound: flight: ', flight)
                    return flight.flight_inventory_tour_id != flight_inventory_tour_id
                })
            }
            console.log('bffs on data: traveller', traveller)
            this.updateFlight(flight_inventory_tour_id, 'Outbound', flight_tour, traveller, custom, this.token)
        })
        bus.$on('set_inbound', (flight_inventory_tour_id, flight_tour, traveller, custom) => {
            let that = this
            if (traveller == null) {
                console.log('set_inbound: no traveller is passed in')
            } else if (this.leadTraveller == null) {
                console.log('set_inbound: no leadTravller')
            } else if (traveller.id == this.leadTraveller.id && traveller.is_lead_booker && !custom) {
                this.unselected_inbound = this.inbound_flights.filter(flight => {
                    return flight.flight_inventory_tour_id != flight_inventory_tour_id
                })
            }
            this.updateFlight(flight_inventory_tour_id, 'Inbound', flight_tour, traveller, custom, this.token)
        })
        bus.$on('customerLoaded', (leadTraveller) => {
            console.log('customerLoaded leadTraveller loaded:', leadTraveller)
            this.leadTraveller = leadTraveller
        })
        bus.$on('additionalTravellersLoaded', (travellers) => {
            this.travellers = travellers
        })

    },
    computed: {
        otherairports: function() {
            const airports = this.airports
            const items  = airports.filter((airport) => {
                return airport.airport_name != this.flight_from;
            })
            return items
        },
        checkCustomButton: function() {
            const customChanges = this.travellerFlightOptions.includes(true)
            const selections = this.selected_outbound && this.selected_inbound

            return selections || customChanges
        }
    },
    methods: {
        dmy(s) {
            return dates.makeDateFromString(s)
        },
        toggleFlights() {
            this.showFlights = !this.showFlights
        },
        flightOptionsCustomer(id) {
            if (typeof this.travellerFlightOptions[id] == 'undefined' || this.travellerFlightOptions.length == 0 || this.travellerFlightOptions[id] == null) {
                Vue.set(this.travellerFlightOptions, id, true)
           } else {
                Vue.set(this.travellerFlightOptions, id, !this.travellerFlightOptions[id])
            }
        },
        customFlights() {
            this.debug && console.log('this.showFlights && this.showCustomFlights', this.showFlights, this.showCustomFlights) 
            this.showCustomFlights = !this.showCustomFlights
            /**
             * when flights are selected, show customers with checkboxes
             */
            if (this.showFlights && this.showCustomFlights) {
                this.debug>1 && console.log('show flights for order', this.order_id)
                axios.get('/api/booking/tourparty', {
                    params: {
                        order_id: this.order_id
                    }
                })
                .then(response => {
                    console.log('get additonal travellers for tour', response)
                    this.travellers = response.data
                })
                .catch(err => {
                    console.log(err)
                })
                    
            }
        },
        async updateFlight(flight_inventory_tour_id, flight_type, flight_tour, customer, custom) {
            
            console.log('[updateFlight] called', this.order_id, this.tour.id, flight_tour, 'flight_inventory_tour_id', flight_inventory_tour_id, flight_type, customer, 'token' + this.token)
            if (customer != null) {
                // booking the flight
                await axios.post(`/api/booking/flight/${customer.customer_id}/${this.tour.id}/${this.order_id}/${flight_type}/${flight_inventory_tour_id}/${custom ? 1 : 0}/${this.token}`)
                .then(response => {
                    console.log('flight booking response', response)

                })  
                .catch(error => {
                    console.log(error)
                })
            // } else {
            //     // group booking by lead
            //     await axios.post(`/api/booking/flight/group/${this.tour.id}/${this.order_id}/${flight_type}/${flight}`)
            //     .then(response => {
            //         console.log('group flight booking response', response)
            //     })  
            //     .catch(error => {
            //         console.log(error)
            //     })

            }
        },
        // loads current flight orders 
        async loadFlights(order_id) {
            console.log('loadFlights called for order ', order_id)
            let that = this
            let outbound = {}
            let inbound = {}
            await axios.get(`/api/booking/flight/orders/${this.order_id}`)
                .then(response => {
                    let orders = response.data.orders
                    // set the selected group flights
                    that.outbound_group_order = orders.filter(order => order.flight_type == 'Outbound' && order.addon == 0)[0]
                    that.inbound_group_order =  orders.filter(order => order.flight_type == 'Inbound' && order.addon == 0)[0]
                    // .filter returns an array, there should only be a single item with addon == 0
                    outbound = that.flights.filter(flight => {
                        return flight.flight_inventory_tour_id == that.outbound_group_order.inventory_tour_id
                    })[0]
                    inbound = that.flights.filter(flight => {
                        return flight.flight_inventory_tour_id == that.inbound_group_order.inventory_tour_id
                    })[0]
                    if (typeof outbound !== 'undefined' && outbound.flight_inventory_tour_id) {
                        that.selected_outbound_flight = outbound.flight_inventory_tour_id
                    }
                    if (typeof inbound !== 'undefined' && inbound.flight_inventory_tour_id) {
                        that.selected_inbound_flight = inbound.flight_inventory_tour_id
                    }
                    that.logging && console.log('SELECTED GROUP FLIGHTS', that.selected_outbound_flight, that.selected_inbound_flight)
                    // process the addons
                    const outbound_addons = orders.filter(order => order.flight_type == 'Outbound' && order.addon == 1)
                    const inbound_addons = orders.filter(order => order.flight_type == 'Inbound' && order.addon == 1)
                    console.log('ADDONS', outbound_addons, inbound_addons)
                    console.log('TRAVELLERS', that.travellers)
                    // addons belong to a customer, and may be active or not?
                })
                .catch(error => {
                    console.log('error loading flights', error)
                })
        },
        async getAirports() {
            var that = this
            await axios.get('/api/booking/airports')
                .then(response => {
                    that.airports = response.data.airports
                })
                .catch(error => console.log(error.message))
        },
        // loads the selectors for the flights related to this tour
        async getFlights(tour) {
            var that = this
            this.debug>3 && console.log('BookingFormFlights: getFlights, tour ', tour)
            await axios.get(`/api/booking/flights/${tour}`)
                .then(response => {
                    this.debug>2 && console.log('&&&& flight response', response)
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
        // addon flights
        addOutboundFlight(flight) {
            this.outbound_flights.push(flight)
            this.debug && console.log('addOutboundFlight: ',this.outbound_flights)
           //bus.$on('set_outbound', (flight_id, flight_tour, flight_traveller) => this.selected_outbound = flight_id)

        },
        addInboundFlight(flight) {
            this.inbound_flights.push(flight)
            this.debug && console.log('addInboundFlight: ', this.inbound_flights)
            // bus.$on('set_inbound', (flight_id, flight_tour, flight_traveller) => this.selected_outbound = flight_id)

        }
    }
}
</script>
