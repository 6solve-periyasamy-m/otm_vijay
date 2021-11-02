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
            <div v-if="showwait">Loading...</div>
            <div class="card-body compress" v-if="showFlights">
                <div class="card-options">
                    <div class="ept-form">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4> Group Flight </h4>
                                <p>Flights for each member, unless custom selections made</p>
                                <booking-form-flight-selector 
                                    v-model="selected_outbound"
                                    :enabled="!travellerFlightOptions.includes(true)"
                                    :custom="false"
                                    :tour="tour"
                                    :token="token"
                                    :traveller="leadTraveller"
                                    :airports="airports"
                                    :flights="outbound_flights"
                                    :types="outbound_type"
                                    :selected_item="selected_outbound_flight">
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
                                    :token="token"
                                    :traveller="leadTraveller"
                                    :airports="airports"
                                    :flights="inbound_flights"
                                    :types="inbound_type"
                                    :selected_item="selected_inbound_flight">
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
                                        :token="token"
                                        :airports="airports"
                                        :flights="flights"
                                        :types="tour_flight_optional_types">
                                    </booking-form-flight-selector>
                                </div>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary" @click="customFlights()">
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
                                <div class="row">
                                    <div class="col-sm-3">
                                        {{ traveller.order_customer_id }} {{ traveller.is_lead_booker ? 'Lead' : 'Additional'}}
                                    </div>
                                    <div class="col-sm-3">
                                        {{ traveller.first_name }}
                                    </div>
                                    <div class="col-sm-3">
                                        {{ traveller.last_name}}
                                    </div>
                                </div>
                                <div class="flight-options" v-if="travellerFlightOptions[traveller.order_customer_id]">
                                    <h5>Flight Options for traveller</h5>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            {{debug>5 ? unselected_outbound : ''}}
                                            <booking-form-flight-selector
                                                v-model="selected_custom_outbound_flight"
                                                :enabled="true"
                                                :custom="true"
                                                :tour="tour"
                                                :token="token"
                                                :traveller="traveller"
                                                :airports="airports"
                                                :flights="unselected_outbound"
                                                :types="outbound_type"
                                                :selected_item="traveller.selected_outbound_addon">
                                            </booking-form-flight-selector>
                                            {{debug>5 ? unselected_inbound : ''}}
                                            <booking-form-flight-selector
                                                v-model="selected_custom_inbound_flight"
                                                :enabled="true"
                                                :custom="true"
                                                :tour="tour"
                                                :token="token"
                                                :traveller="traveller"
                                                :airports="airports"
                                                :flights="unselected_inbound"
                                                :types="inbound_type"
                                                :selected_item="traveller.selected_inbound_addon">
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
    props: ['tour', 'order_id', 'token', 'leadTraveller'],
    data() {
        return {
            debug: false,
            activated: false,
            showwait: false,
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
            // flight_tour: {},
            // flight_traveller: {},

            // for control of custom flight selection
            selected_outbound: null,
            selected_inbound: null,
            custom_outbound: null,
            custom_inbound: null,
            unselected_outbound: [],
            unselected_inbound: [],
            travellerFlightOptions: [],
            
            selected_outbound_flight: null,
            selected_inbound_flight: null,
            selected_custom_outbound_flight: null,
            selected_custom_inbound_flight: null,
            outbound_group_order: {},
            inbound_group_order: {},
            ready: false,
            // token: '',
            orderset: []
        }
    },
    async mounted() {
        let that = this
        bus.$on('debugOverride', (debug) => that.debug = debug)
        this.debug>1 && console.log('BFF mounted', this.order_id, that.token, that.leadTraveller);
        await this.getFlights(this.tour.id)
        await this.getTourParty(this.order_id)
        await this.loadFlightsForOrder(this.order_id)
        this.outbound_flights = this.flights.filter((flight) => flight.flight_type == 'Outbound')
        this.inbound_flights = this.flights.filter((flight) => flight.flight_type == 'Inbound')
        // this.other_flights = this.flights.filter((flight) => flight.flight_type != 'Outbound' && flight.flight_type != 'Inbound')
        this.debug && console.log('BookingFormFlights component mounted with flights: ', this.flights, ' for tour ', this.tour, ' check_out ', this.order_id)
        this.debug>1 && console.log('MOUNTED: outbound flights', this.outbound_flights)
        this.debug>1 && console.log('MOUNTED: inbound flights', this.inbound_flights)
        that.ready = true
        that.activated = true
    },
    created() {
        let that = this

        bus.$on('set_outbound', (flight_inventory_tour_id, flight_tour, traveller, custom, token) => {
            that.debug>4 && console.log('BFF set_outbound event: ', flight_inventory_tour_id, flight_tour, traveller, custom)
            if (traveller == null) {
                console.log('set_outbound: no traveller is passed in')
            } else if (that.leadTraveller == null) {
                console.log('set_outbound: leadTravller NOT set')
            } else if (traveller.id == this.leadTraveller.id && traveller.is_lead_booker && !custom) {
                that.unselected_outbound = this.outbound_flights.filter(flight => {
                    that.debug > 2 && ('unselected outbound: flight: ', flight)
                    return flight.flight_inventory_tour_id != flight_inventory_tour_id
                })
            }
            this.updateFlight(flight_inventory_tour_id, 'Outbound', flight_tour, traveller, custom, token)
        })

        bus.$on('set_inbound', (flight_inventory_tour_id, flight_tour, traveller, custom, token) => {
            if (traveller == null) {
                console.log('set_inbound: no traveller is passed in')
            } else if (that.leadTraveller == null) {
                console.log('set_inbound: no leadTravller')
            } else if (traveller.id == that.leadTraveller.id && traveller.is_lead_booker && !custom) {
                that.unselected_inbound = that.inbound_flights.filter(flight => {
                    return flight.flight_inventory_tour_id != flight_inventory_tour_id
                })
            }
            that.updateFlight(flight_inventory_tour_id, 'Inbound', flight_tour, traveller, custom, token)
        })

        bus.$on('removeBooking', (booking, flight_type, traveller) => {
            console.log('event remove ', flight_type, ' Booking', booking, 'for ', traveller, 'token', that.token)
            const item = this.orderset.filter(ordr => ordr.inventory_tour_id === booking &&
                ordr.order_customer_id == traveller.order_customer_id);
            if (item.length === 1) {
                console.log('deleting booking', item[0].cod_id)
            }
            // this method is only for removing custom (addon) flights (you can only change group bookings)
            const record = {
                order_customer_id: traveller.order_customer_id,
                inventory_tour_id: booking,
                flight_type: flight_type,
                custom: true,
                token: that.token
            }
            console.log('remove daat', record)
            axios.post(`/api/booking/flights/remove/flight`, record)
                 .then(response => {
                    that.debug>3 && console.log('remove flight response', response.data.flight)
                    const deleted_flight = response.data.flight
                    bus.$emit('flightRemoved', deleted_flight.id)
                })
                .catch(error => console.log(error));
        })
    },
    computed: {
        otherairports: function() {
            const airports = this.airports
            const items = airports.filter((airport) => {
                return airport.name != this.flight_from;
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
        dmy(s) {
            return dates.makeDateFromString(s)
        },
        async toggleFlights() {
            if (this.activated) {
                this.showFlights = !this.showFlights
            }
            if (this.showFlights) {
                await this.loadFlightsForOrder(this.order_id)
                this.showwait = false
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
            await axios.get('/api/booking/tourparty', {
                    params: {
                        order_id: order_id
                    }
                })
                .then(response => {
                    that.debug > 1 && console.log('getTourParty, response:', response)
                    that.travellers = response.data
                })
                .catch(err => {
                    console.log('ERROR loading tour party', err)
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
        async updateFlight(flight_inventory_tour_id, flight_type, flight_tour, customer, custom, token) {
            this.debug > 3 && console.log('updateFlight()', flight_inventory_tour_id, flight_type, flight_tour, customer, custom, token);
            let that = this
            if (customer == null) {
                console.log('WARNING: updateFlight customer data missing')
                alert('Form data missing, please refresh or contact support')
                return
            }
            const url = '/api/booking/flight'
            const data = {
                customer_id: customer.customer_id,
                tour_id: that.tour.id,
                order_id: that.order_id,
                flight_type: flight_type,
                inventory_tour_id: flight_inventory_tour_id,
                custom: custom,
                token: token
            }
            await axios.post(url, data)
                .then(response => {
                    that.debug > 3 && console.log('flight booking response', response)
                    that.loadFlightsForOrder(that.order_id)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        flightSelected(type, addon, orders, traveller) {
            const that = this
            if (traveller == null) {
                console.log('WARNING: flightSelected - traveller is null')
                alert('Form data incomplete, please refresh or contact support')
                return
            }
            let order = orders.filter(ordr => ordr.flight_type == type &&
                ordr.addon == addon &&
                ordr.order_customer_id == traveller.order_customer_id)

            if (typeof order == 'undefined' || order == null || order.length == 0) {
                console.log('WARNING: flightSelected no ' + type + ' order?')
             //   alert('Form data incorrect, please refresh or contact support')
                return null
            }
            const order_selected = order[0]
            const flight = that.flights.filter(flight => {
                return flight.flight_inventory_tour_id == order_selected.inventory_tour_id
            })[0]
            flight.cod = order_selected.cod_id
            this.debug>2 && console.log('>>>>> order_selected:', order_selected, ' flight type ' + type + ' flight ...', flight)

            return flight
        },

        // loads current flight orders 
        async loadFlightsForOrder(order_id) {
            if (!order_id) {
                alert('Form data seems to have missing data, please refresh or contact support')
                console.log('WARNING: load flights for order missing an order?');
                return
            } else {
                this.debug>3 && console.log('INFO: loadFlightsForOrder called for order ', order_id)
            }
            let that = this
            let outbound = {}
            let inbound = {}

            this.showwait = true
            await axios.get(`/api/booking/flight/orders/${this.order_id}`)
                .then(response => {
                    that.orderset = response.data.orders
                    const orders = that.orderset

                    that.debug>1 && console.log('loadFlightsForOrder >>>> flights for order', orders, that.travellers[0])

                    //this.flightSelected('Inbound', 1, orders, that.travellers[1])
                    outbound = that.flightSelected('Outbound', 0, orders, that.travellers[0])
                    inbound = that.flightSelected('Inbound', 0, orders, that.travellers[0])

                    // set the selected_outbound_flight (group selector)
                    if (typeof outbound !== 'undefined' && outbound != null && outbound.flight_inventory_tour_id) {
                        that.selected_outbound_flight = outbound.flight_inventory_tour_id
                        if (that.debug>3) console.log('loadFlightsForOrder SELECTED OUT FLIGHT', that.selected_outbound_flight)
                    }

                    if (typeof inbound !== 'undefined' && inbound != null && inbound.flight_inventory_tour_id) {
                        that.selected_inbound_flight = inbound.flight_inventory_tour_id
                        if (that.debug>3) console.log('loadFlightsForOrder SELECTED IN FLIGHTS', that.selected_inbound_flight)
                    }

                    that.debug>2 && console.log('loadFlightsForOrder SELECTED GROUP FLIGHTS', that.selected_outbound_flight, that.selected_inbound_flight)

                    that.unselected_outbound = that.flights.filter(flight => {
                        return flight.flight_inventory_tour_id != that.selected_outbound_flight &&
                            flight.flight_type == 'Outbound'
                    })
                    that.unselected_inbound = that.flights.filter(flight => {
                        return flight.flight_inventory_tour_id != that.selected_inbound_flight &&
                            flight.flight_type == 'Inbound'
                    })

                    // process the addons
                    const outbound_addons = orders.filter(order => order.flight_type == 'Outbound' && order.addon == 1)
                    const inbound_addons = orders.filter(order => order.flight_type == 'Inbound' && order.addon == 1)
                    if (outbound_addons) {
                        that.debug>2 && console.log('loadFlightsForOrder ADDONS', outbound_addons)
                        that.travellers.map((traveller, key) => {
                            outbound = that.flightSelected('Outbound', 1, orders, traveller)
                            //  :selected_item="traveller.selected_outbound_addon">
                            if (typeof outbound !== 'undefined' && outbound != null && outbound.flight_inventory_tour_id) {
                                traveller['selected_outbound_addon'] = outbound.flight_inventory_tour_id
                                that.$set(that.travellers, key, traveller)
                                this.debug>3 && console.log('EMITTING setCustomFlightsForTraveller outbound', traveller, outbound)
                                bus.$emit('setCustomFlightsForTraveller', traveller, outbound.flight_inventory_tour_id)
                            }
                        })
                    }
                    if (inbound_addons) {
                        that.debug>2 && console.log('ADDONS', inbound_addons)
                        that.travellers.map((traveller, key) => {
                            inbound = that.flightSelected('Inbound', 1, orders, traveller)
                            //  :selected_item="traveller.selected_outbound_addon">
                            if (typeof inbound !== 'undefined' && inbound != null && inbound.flight_inventory_tour_id) {
                                traveller['selected_inbound_addon'] = inbound.flight_inventory_tour_id
                                // this should work, TODO: check it seting the inventory flight id into the component?
                                that.$set(that.travellers, key, traveller)
                                // TODO: may want to check if these worked, or if required
                                // that.$set(that.travellers[key], 'selected_inbound_addon', inbound.flight_inventory_tour_id)
                                // that.selected_inbound_addon = inbound.flight_inventory_tour_id
                                bus.$emit('setCustomFlightsForTraveller', traveller, inbound.flight_inventory_tour_id)
                            }
                        })
                    }
                    that.showwait = false
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
                .catch(error => console.log('Error loading airports', error.message))
        },
        // loads the selectors for the flights related to this tour
        async getFlights(tour) {
            var that = this
            this.debug>3 && console.log('BookingFormFlights: getFlights, tour ', tour)
            await axios.get(`/api/booking/flights/${tour}`)
                .then(response => {
                    this.debug>2 && console.log('&&&& flight response', response)
                    that.flights = response.data.data
                    that.outbound_flights = that.flights.filter((flight) => flight.flight_type == 'Outbound')
                    that.inbound_flights = that.flights.filter((flight) => flight.flight_type == 'Inbound')

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
    }
}
</script>
