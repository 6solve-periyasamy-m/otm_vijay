<template>
    <div class="booking-container">
        <div class="card card-options">
            <div class="card-header" id="headingTwo">
                <h5 class="dropdown-button">
                    <button :disabled="!booking_token" class="btn btn-link collapsed cardhead" @click="toggleAdditional"><font-awesome-icon icon="book-reader" />
                        Additional Travellers
                    </button>
                </h5>
                <p class="caption" v-if="!showAdditional">
                    <font-awesome-icon icon="arrow-right" /> 
                    {{ travellerBookings.length ? `Group Size: ${travellerBookings.length+1}`: 'Please add all travellers to your tour party'}}
                </p>
            </div>
            <div class="card-body" v-show="showAdditional">
                <div v-if="showInstruction">
                    <h4>Additional Travellers</h4>
                    <p>Please input data for any additional travellers that are accompanying you.</p>
                    <p>Use the add button to add more travellers or remove to delete entries.</p>
                </div>
                <div v-for="item in travellerBookings" :key="item.id">
                    
                    <booking-form-add-traveller 
                        :booking_token="booking_token"
                        :tour="tour"
                        :traveller="item"
                        :form_id="item.id"
                    >
                    <button v-if="item.id" class="btn btn-default btn-remove" slot="remove" @click="removeTraveler(item)">X {{debug?item.id:''}}</button>
                    </booking-form-add-traveller>
                </div>
                <div class="controls">
                    <button type="button" class="btn btn-primary" @click="addAdditional">Add additional travellers</button>
                    <button type="button" class="btn btn-primary" @click="submit">All travellers entered</button>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
.btn.btn-remove {
    color: red;
    border: none;
    &:hover {
        background: red;
        color: black;
    }
}
</style>
<script>
    import axios from 'axios'
    import { bus } from '../bus' 
    export default {
        props: ['tour'],
        data() {
            return {
                debug: false,
                moduleName: 'AdditionalTravellers',
                booking_token: null,
                id: 0,
                formId: 0,
                showAdditional: false,
                showInstruction: true,
                travellerBookings: [],
                leadTraveller: {},
                booking: {},
                removed: false
            }
        },
        created() {
            let that = this
            this.debug && console.log('*** travellerBookings created: check props', this.tour, this.leadTraveller )

            bus.$on('setBookingToken', (token) => {
                that.booking_token = token
                that.debug && console.log(`>>> ${that.moduleName} created for booking ${that.booking_token}`)
                that.loadLeadTraveler(token).then(() => that.loadTravellerBookings());
            })

            bus.$on('setLeadTraveller', customer => {
              that.debug>1 && console.log(`${that.moduleName} set the Lead Traveller`, customer)
              that.leadTraveller = customer
            })

            bus.$on('checkEmailUnique', email => {
                if (email == null) {
                    return
                }
                that.travellerBookings.map(traveller => {
                    if (traveller.email == email) {
                        bus.$emit('emailUsed', true)
                    }
                })
            })
            bus.$on('removeTraveler', traveler => {
                that.removeTraveler(traveler)
            })
        },
        mounted() {
            this.debug && console.log(`${this.moduleName} mounted`)
        },
        methods: {
            async loadLeadTraveler(token) {
                let that = this
                await axios.get(`/api/booking/customer/${token}`)
                    .then(response => {
                        that.debug>1 && console.log('ADDITIONAL GET LEAD response:', response)
                        that.leadTraveller = response.data.customer
                    })
                    .catch(error => console.log(error))
            },
            submit() {
                this.$forceUpdate()
                this.toggleAdditional()
            },
            getFormId() {
                return this.travellerBookings.length + 1;   
                // return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            },
            removeTraveler(traveler) {
                let that = this
                if (confirm(`Remove traveler?`) !== true) {
                    return
                }
                if (traveler.id) {
                    axios.post(`/api/booking/additional-traveller/remove`, {
                        customer_id: traveler.id,
                        booking_token: this.booking_token
                    })
                    .then(response => {
                        console.log('additionalTraveller: remove', response)
                        that.removed = response.data.success
                        if (that.removed) {
                            that.loadTravellerBookings()
                        }
                    })
                } else {
                    console.log('remove traveler could not remove', traveler)
                }
            },
            toggleAdditional() {
                this.showAdditional = !this.showAdditional
            },
            async loadTravellerBookings(init = false) {
                let that = this
                this.debug && console.log(`ADDITIONAL: loading additional travellers for ${this.booking_token}`, that.leadTraveller)
                await axios.get(`/api/booking/travellers/${this.booking_token}`)
                    .then(response => {
                        if (response.data.success) {
                            const travellers = response.data.travellers
                            that.debug && console.log('ADDITIONAL: travellers', travellers, that.leadTraveller);
                            that.travellerBookings = travellers.filter(traveller => traveller.id !== that.leadTraveller.id)
                            that.debug && console.log('ADDITIONAL: travellerBookings', that.travellerBookings);
                            bus.$emit('AdditionalTravelersLoaded', that.travellerBookings, true)
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    })
            },
            addAdditional() {
                this.formId = this.getFormId()
                this.showInstruction = false
                this.travellerBookings.push(`traveller_${this.formId}`) 
                this.showAdditional = true
                this.debug && console.log('ADDITIONAL addAdditional form:', this.formId)
                // Event handler in BookingFormAddTraveller used this.formId (which was not defined?)
                bus.$emit('addTraveller', this.formId)
            }
        }
    }
</script>
