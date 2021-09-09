<template>
    <div class="container">
        <div class="card card-options">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-1">
                    <button class="btn btn-link collapsed cardhead" @click="toggleAdditional">
                        Additional Travellers <div v-if="form_info">on order {{order_id}}</div>
                    </button>
                    <p class="caption" v-if="!lead_traveller && !showAdditional">{{ additional.length ? `Group Size: ${additional.length+1}`: 'Please add all travellers to your tour party'}}</p>
                </h5>
            </div>
            <div class="card-body" v-show="showAdditional">
                <div v-if="showInstruction">
                    <p>Please input data for any additional travellers that are accompanying you.</p>
                    <p>Use the add button to add more travellers or remove to delete entries.</p>
                </div>
                <div v-for="item in additional" :key="item.id">
                    <booking-form-add-traveller :traveller="item" :order_id="order_id" :tour="tour" @remove="removeTraveller"></booking-form-add-traveller>
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
    import { bus } from '../bus' 
    export default {
        props: ['form_info', 'order_id', 'tour'],
        data() {
            return {
                debug: false,
                id: 0,
                formId: 0,
                lead_traveller: false, // TODO: this should be set by the event bus
                showAdditional: false,
                showInstruction: true,
                additional: []
            }
        },
        created() {
            this.debug && console.log('Booking form Additional Customer Component created.', this.order_id, this.tour)
            bus.$on('additionalTravellersLoaded', (customers) => {
                this.debug && console.log('additionalTravellersLoaded signal', customers)
                this.setCustomers(customers)
            })
        },
        methods: {
            submit() {
                this.$forceUpdate()
                this.toggleAdditional()
            },
            setCustomers(customers) {
                this.debug && console.log('BookingFormAdditional.setCustomers(customers) customers:', customers)
                customers.forEach(customer => {
                    if (!customer.is_lead_booker) {
                        customer.formId = this.getFormId()
                        customer.customer_id = customer.id
                        this.additional.push(customer)
                    }
                })
            },
            getFormId() {
                return this.id++
                
                return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            },
            removeTraveller() {
                this.debug  && console.log('removing additional traveller ', this.formId)
                return false
            },
            toggleAdditional() {
                this.showAdditional = !this.showAdditional
            },
            addAdditional() {
                const formId = this.getFormId()
                this.showInstruction = false
                this.additional.push(`traveller_${formId}`) 
                this.showAdditional = true
                this.debug && console.log('additional added')
                bus.$emit('addTraveller', this.formId)
            }
        }
    }
</script>
