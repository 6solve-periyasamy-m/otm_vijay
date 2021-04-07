<template>
    <div class="container">
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-1">
                    <button class="btn btn-link collapsed cardhead" @click="toggleAdditional">
                        Additional Travellers 
                    </button>
                    <p class="caption" v-if="!lead_traveller && !showTraveller">{{ additional.length ? `Group Size: ${additional.length+1}`: 'Please add all travellers to your tour party'}}</p>
                </h5>
            </div>
            <div class="card-body" v-if="showAdditional">
                <div v-if="showInstruction">
                    <p>Please input data for any additional travellers that are accompanying you.</p>
                    <p>Use the add button to add more travellers or remove to delete entries.</p>
                </div>
                <div v-for="item in additional" :key="item.name">
                    <booking-form-add-traveller :order_id="order_id" :tour="tour" :formId="item" @remove="removeTraveller"></booking-form-add-traveller>
                </div>
                <button type="button" class="btn btn-success" @click="addAdditional">Add traveller</button>
                <button
                    type="button"
                    class="btn btn-success"
                    @click="showAdditional=false">
                    All travellers entered
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import { bus } from '../bus' 
    export default {
        props: ['order_id', 'tour'],
        data() {
            return {
                id: 0,
                showAdditional: false,
                showInstruction: true,
                additional: []
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            formId() {
                return this.id++
                
                return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            },
            removeTraveller() {
                console.log('removing ', this.formId)
            },
            toggleAdditional() {
                this.showAdditional = !this.showAdditional
            },
            addAdditional() {
                const formId = this.formId()
                this.showInstruction = false
                this.additional.push(`traveller_${formId}`) 
                this.showAdditional = true
                console.log('additional added')
                bus.$emit('addTraveller', this.formId)
            }
        }
    }
</script>
