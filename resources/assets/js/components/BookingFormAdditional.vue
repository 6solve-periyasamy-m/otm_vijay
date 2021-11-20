<template>
    <div class="container">
        <div class="card card-options">
            <div class="card-header" id="headingTwo">
                <h5 class="dropdown-button">
                    <p v-if="!lead_traveller && !showAdditional">{{ additionalTravellers.length ? `Group Size: ${additionalTravellers.length+1}`: 'Please add all travellers to your tour party'}}</p>
                    <button :disabled="!booking_token" class="btn btn-link collapsed cardhead" @click="toggleAdditional">
                        <font-awesome-icon icon="book-reader" /> Additional Travellers <div v-if="form_info">on order {{order_id}}</div>
                    </button>
                </h5>
            </div>
            <div class="card-body" v-show="showAdditional">
                <div v-if="showInstruction">
                    <p>Please input data for any additional travellers that are accompanying you.</p>
                    <p>Use the add button to add more travellers or remove to delete entries.</p>
                </div>
                <div v-for="item in additionalTravellers" :key="item.id">
                    <booking-form-add-traveller 
                        :booking_token="booking_token"
                        :tour="tour"
                        :traveller="item"
                        :form_id="item.id"
                        @remove="removeTraveller">
                    </booking-form-add-traveller>
                </div>
                <button type="button" 
                    class="btn btn-primary" 
                    @click="addAdditional">Add traveller</button>
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="submit">
                    All travellers entered
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import { bus } from '../bus' 
    export default {
        props: ['form_info', 'booked', 'tour', 'lead_traveller'],
        data() {
            return {
                debug: false,
                moduleName: 'Additional',
                booking_token: null,
                id: 0,
                formId: 0,
                showAdditional: false,
                showInstruction: true,
                additionalTravellers: [],
                booking: {}
            }
        },
        created() {
            let that = this
            this.debug && console.log('AdditionalTravellers created: check props', this.form_info, this.booked, this.tour, this.is_lead_traveller)
            
            bus.$on('setBookingToken', (token) => {
                that.booking_token = token
                that.debug && console.log(`>>> ${that.moduleName} created for booking ${that.booking_token}`)            
                that.loadAdditionalTravellers()
            })
            bus.$on('checkEmailUnique', email => {
                that.additionalTravellers.map(traveller => {
                    if (traveller.email == email) {
                        bus.$emit('emailUsed', true)
                    }
                })
            })

        },
        mounted() {
            console.log(`${this.moduleName} mounted`)
        },
        methods: {
            submit() {
                this.$forceUpdate()
                this.toggleAdditional()
            },
            getFormId() {
                return this.additionalTravellers.length + 1; //this.id++
                
                return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            },
            removeTraveller() {
                this.debug  && console.log('...... removing additional traveller ', this.formId)
                return false
            },
            toggleAdditional() {
                this.showAdditional = !this.showAdditional
            },
            loadAdditionalTravellers() {
                let that = this
                this.debug && console.log(`...... loading additional travellers for ${this.booking_token}`)
                axios.get(`/api/booking/travellers/${this.booking_token}`)
                    .then(response => {
                        console.log(response)
                        if (response.data.success) {
                            that.additionalTravellers = response.data.travellers
                            bus.$emit('additionalTravellersLoaded', that.additionalTravellers)
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    })
            },
            addAdditional() {
                this.formId = this.getFormId()
                this.showInstruction = false
                this.additionalTravellers.push(`traveller_${this.formId}`) 
                this.showAdditional = true
                this.debug && console.log('????? additional added', this.formId)
                // Event handler in BookingFormAddTraveller used this.formId (which was not defined?)
                bus.$emit('addTraveller', this.formId)
            }
        }
    }
</script>
