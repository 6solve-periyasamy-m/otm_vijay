<template>
    <div class="container">
        <div class="card card-options">
            <div class="card-header">
                <h5 class="mb-1">
                    <button :disabled="ready ? false : true" class="btn btn-link cardhead" @click="toggleFlights">
                                Flights
                            </button>
                </h5>
            </div>
            <div class="card-body compress" v-if="showFlights">
                <div class="container">
                    <div class="card-options">
                        <div class="ept-form">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4> Group Flight </h4>
                                    <p>Flights for each member, unless custom selections made</p>
                                    <booking-form-flight-selector v-model="selected_outbound" :enabled="!travellerFlightOptions.includes(true)" :custom="false" :tour="tour" :traveller="leadTraveller" :airports="airports" :flights="outbound_flights" :types="outbound_type" :selected_item="selected_outbound_flight">
                                    </booking-form-flight-selector>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <booking-form-flight-selector v-model="selected_inbound" :enabled="!travellerFlightOptions.includes(true)" :custom="false" :tour="tour" :traveller="leadTraveller" :airports="airports" :flights="inbound_flights" :types="inbound_type" :selected_item="selected_inbound_flight">
                                    </booking-form-flight-selector>
                                </div>
                            </div>
                            <div class="row" v-if="showOtherFlights">
                                <div class="col-sm-9">
                                    <h4> Add On Flights </h4>
                                    <div v-for="flight in other_flights" :key="flight.id">
                                        <booking-form-flight-selector v-model="other_flight_selected" :tour="tour" :airports="airports" :flights="flights" :types="tour_flight_optional_types">
                                        </booking-form-flight-selector>
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary" :disabled="disableCustomButton" @click="customFlights()">
                                    Customise
                                </button>
                                    <button class="btn btn-primary" @click="toggleFlights">
                                    Close
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
                                    <div v-if="debug && traveller.selected_outbound_addon">Custom outbound selected {{outbound_flights[traveller.selected_outbound_addon]}}</div>
                                    <div v-if="debug  && traveller.selected_inbound_addon">Custom inbound selected {{inbound_flights[traveller.selected_inbound_addon]}}</div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            View <input class="`customer-flight-${traveller.id}`" type="checkbox" name="custom" :checked="flightChecked(traveller)" @change="flightOptionsCustomer(traveller.order_customer_id)">
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
                                                    <booking-form-flight-selector v-model="custom_outbound" :enabled="true" :custom="true" :tour="tour" :traveller="traveller" :airports="airports" :flights="unselected_outbound" :types="outbound_type" :selected_item="traveller.selected_outbound_addon">
                                                    </booking-form-flight-selector>
                                                    <booking-form-flight-selector v-model="custom_inbound" :enabled="true" :custom="true" :tour="tour" :traveller="traveller" :airports="airports" :flights="unselected_inbound" :types="inbound_type" :selected_item="traveller.selected_inbound_addon">
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
    props: ['tour'],
    data() {
        return {
            debug: 0,
            activated: false,

            token: null,
            order_id: 0,
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
            inbound_group_order: {},
            ready: false
        }
    },
    async mounted() {
        let that = this
        bus.$on('debugOverride', (debug) => that.debug = debug)
        bus.$on('customerLoaded', (leadTraveller => that.leadTraveller = leadTraveller))
        bus.$on('setOrderToken', (token, order_id) => {
            that.token = token
            that.order_id = order_id
            this.debug && console.log('BFFlights: setOrderToken ::  ', token, order_id)
        })

        await this.getFlights(this.tour.id)
        await this.getTourParty(this.order_id)

        this.debug && console.log('MOUNTED: order_id & gettourParty', this.order_id, this.travellers)

        await this.loadFlights(this.order_id)
        this.outbound_flights = this.flights.filter((flight) => flight.flight_type == 'Outbound')
        this.inbound_flights = this.flights.filter((flight) => flight.flight_type == 'Inbound')
        this.other_flights = this.flights.filter((flight) => flight.flight_type != 'Outbound' && flight.flight_type != 'Inbound')
        this.debug && console.log('BookingFormFlights component mounted with flights: ', this.flights, ' for tour ', this.tour, ' check_out_date_time ', this.order_id)
        this.debug > 1 && console.log('MOUNTED: outbound flights', this.outbound_flights)
        this.debug > 1 && console.log('MOUNTED: inbound flights', this.inbound_flights)

        this.ready = true
        // TODO: get customer_order_details for type='flight' customer_order_id = this.order_id
        //       select the booked flights in this.outbound_flights, this.inbound_flights
        //       if this is a confirmed order, 
        //          lock the flights for group
        //          if custom flights are changed, 
        //              add transactions to cancel previous booking and rebook
    },
    created() {
        bus.$on('set_outbound', (flight_inventory_tour_id, flight_tour, traveller, custom) => {
            let that = this
            this.debug > 4 && console.log('BFF set_outbound event: ', flight_inventory_tour_id, flight_tour, traveller, custom)
            if (traveller == null) {
                console.log('set_outbound: no traveller is passed in')
            } else if (this.leadTraveller == null) {
                console.log('set_outbound: leadTravller NOT set')
            } else if (traveller.id == this.leadTraveller.id && traveller.is_lead_booker && !custom) {
                this.unselected_outbound = this.outbound_flights.filter(flight => {
                    this.debug > 2 && ('unselected outbound: flight: ', flight)
                    return flight.flight_inventory_tour_id != flight_inventory_tour_id
                })
            }
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
            this.debug > 2 && console.log('BFF:  customerLoaded leadTraveller loaded:', leadTraveller)
            // leadTraveller['selected_outbound_addon'] = null
            // leadTraveller['selected_inbound_addon'] = null
            this.leadTraveller = leadTraveller
        })
        bus.$on('additionalTravellersLoaded', (travellers) => {
            this.debug > 2 && console.log('BFF: additionalTravellers loaded', travellers)
            // travellers.map(traveller => {
            //     traveller['selected_outbound_addon'] = null
            //     traveller['selected_inbound_addon'] = null
            // })
            this.travellers = travellers
            this.activated = true
        })
    },
    computed: {
        otherairports: function() {
            const airports = this.airports
            const items = airports.filter((airport) => {
                return airport.airport_name != this.flight_from;
            })
            return items
        },
        disableCustomButton: function() {
            const customChanges = this.travellerFlightOptions.includes(true)
            const selections = this.selected_outbound || this.selected_inbound
            return selections || customChanges
        }
    },
    methods: {
        flightChecked(traveller) {
            if (traveller.selected_outbound_addon || traveller.selected_inbound_addon) {
                return true
            }
            return true
        },
        dmy(s) {
            return dates.makeDateFromString(s)
        },
        toggleFlights() {
            if (this.activated) {
                this.showFlights = !this.showFlights
            }
        },
        flightOptionsCustomer(id) {
            if (typeof this.travellerFlightOptions[id] == 'undefined' || this.travellerFlightOptions.length == 0 || this.travellerFlightOptions[id] == null) {
                Vue.set(this.travellerFlightOptions, id, true)
            } else {
                Vue.set(this.travellerFlightOptions, id, !this.travellerFlightOptions[id])
            }
        },
        async getTourParty(order_id) {
            let that = this
            axios.get('/api/booking/tourparty', {
                    params: {
                        order_id: order_id
                    }
                })
                .then(response => {
                    console.log('get additonal travellers for tour', response)
                    that.travellers = response.data
                })
                .catch(err => {
                    console.log(err)
                })

        },
        hasCustomFlights(traveller) {
            if (traveller.order_customer_id) {
                this.flightOptionsCustomer(traveller.order_customer_id)
                return true
            }
            return false
        },
        customFlights() {
            let state = this.showCustomFlights
            const that = this
            this.travellers.map(traveller => {
                if (that.hasCustomFlights(traveller)) {
                    state = false
                }
            })

            this.showCustomFlights = !state
        },
        async updateFlight(flight_inventory_tour_id, flight_type, flight_tour, customer, custom) {
            this.debug > 4 && console.log('[updateFlight] called', this.order_id, this.tour.id, flight_tour, 'flight_inventory_tour_id', flight_inventory_tour_id, flight_type, customer, 'token' + this.token)
            this.debug > 6 && console.log('updateFlight()', flight_inventory_tour_id, flight_type, flight_tour, customer, custom);
            if (customer != null) {
                const url = `/api/booking/flight/${customer.customer_id}/${this.tour.id}/${this.order_id}/${flight_type}/${flight_inventory_tour_id}/${custom ? 1 : 0}/${this.token}`
                await axios.post(url)
                    .then(response => {
                        console.log('flight booking response', response)
                    })
                    .catch(error => {
                        console.log(error)
                    })
            }
        },
        selected_flight(type, addon, orders, traveller) {
            const that = this
            that.debug > 4 && console.log('SELECTED FLIGHT for ', type, addon, traveller)
            that.debug > 4 && console.log('CHECK ORDERS', orders)
            let order = orders.filter(order => order.flight_type == type && order.addon == addon && order.orders_customer_id == traveller.order_customer_id)[0]
            if (!order) {
                return null
            }
            const flight = that.flights.filter(flight => {
                that.debug > 4 && console.log('ORDER', order)
                return flight.flight_inventory_tour_id == order.inventory_tour_id
            })[0]

            return flight
        },
        // loads current flight orders 
        async loadFlights(order_id) {
            this.debug > 2 && ('loadFlights called for order ', order_id)
            let that = this
            let outbound = {}
            let inbound = {}
            await axios.get(`/api/booking/flight/orders/${this.order_id}`)
                .then(response => {
                    let orders = response.data.orders
                    that.debug > 3 && console.log('>>>> flights for order', orders, that.travellers[0])

                    //this.selected_flight('Inbound', 1, orders, that.travellers[1])
                    outbound = that.selected_flight('Outbound', 0, orders, that.travellers[0])
                    inbound = that.selected_flight('Inbound', 0, orders, that.travellers[0])

                    // set the selected_outbound_flight (group selector)
                    if (typeof outbound !== 'undefined' && outbound != null && outbound.flight_inventory_tour_id) {
                        that.selected_outbound_flight = outbound.flight_inventory_tour_id
                    }
                    if (typeof inbound !== 'undefined' && outbound != null && inbound.flight_inventory_tour_id) {
                        that.selected_inbound_flight = inbound.flight_inventory_tour_id
                    }

                    that.debug > 2 && console.log('SELECTED GROUP FLIGHTS', that.selected_outbound_flight, that.selected_inbound_flight)
                    // process the addons
                    const outbound_addons = orders.filter(order => order.flight_type == 'Outbound' && order.addon == 1)
                    const inbound_addons = orders.filter(order => order.flight_type == 'Inbound' && order.addon == 1)

                    if (outbound_addons) {
                        that.debug > 2 && console.log('ADDONS', outbound_addons)
                        that.debug > 2 && console.log('TRAVELLERS', that.travellers)
                        that.travellers.map((traveller, key) => {
                            that.debug > 4 && console.log('MAPPING ORDERs', key, orders)
                            outbound = that.selected_flight('Outbound', 1, orders, traveller)
                            //  :selected_item="traveller.selected_outbound_addon">
                            if (typeof outbound !== 'undefined' && outbound != null && outbound.flight_inventory_tour_id) {
                                traveller['selected_outbound_addon'] = outbound.flight_inventory_tour_id
                                that.$set(that.travellers, key, traveller)
                                //console.log('EMITTING setCustomFlightsForTraveller outbound')
                                //bus.$emit('setCustomFlightsForTraveller', traveller, outbound.flight_inventory_id)
                            }
                        })
                    }

                    if (inbound_addons) {
                        that.debug > 2 && console.log('ADDONS', inbound_addons)
                        that.debug > 2 && console.log('TRAVELLERS', that.travellers)
                        that.travellers.map((traveller, key) => {
                            that.debug > 4 && console.log('MAPPING ORDERs', key, orders)
                            inbound = that.selected_flight('Inbound', 1, orders, traveller)
                            //  :selected_item="traveller.selected_outbound_addon">
                            if (typeof inbound !== 'undefined' && inbound != null && inbound.flight_inventory_tour_id) {
                                traveller['selected_inbound_addon'] = inbound.flight_inventory_tour_id

                                // this should work, but it is not seting the inventory flight id into the component
                                // that.$set(that.travellers, key, traveller)

                                // this also should work, but also not setting it in the selectors traveller data
                                // that.$set(that.travellers[key], 'selected_inbound_addon', inbound.flight_inventory_tour_id)
                                that.debug > 4 && console.log('SETTING INBOUND ADDON', that.travellers)
                                // that.selected_inbound_addon = inbound.flight_inventory_tour_id
                                //console.log('EMITTING setCustomFlightsForTraveller inbound')
                                //bus.$emit('setCustomFlightsForTraveller', traveller, inbound.flight_inventory_id)
                            }
                        })
                    }

                    that.debug > 2 && console.log('TRAVS', that.travellers)

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
            this.debug > 3 && console.log('BookingFormFlights: getFlights, tour ', tour)
            await axios.get(`/api/booking/flights/${tour}`)
                .then(response => {
                    this.debug > 2 && console.log('&&&& flight response', response)
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
            this.debug && console.log('addOutboundFlight: ', this.outbound_flights)
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
