<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-1">
                    <button class="btn btn-link cardhead" @click="toggleActivities">
                        Activities
                    </button>
                    <p>Activities planned as part of your package, plus available options for each traveller in your group.</p>
                </h5>
            </div>
            <div class="card-body compress" v-if="activated">
                Activities Activated
            </div>
        </div>
    </div>
</template>
<script>
import dates from '../utilities'
import { bus } from '../bus'
import Vue from 'vue'
export default {
    props: ['tour', 'order_id', 'order_token'],
    data() {
        return {
            debug: false,
            activated: false,
        }
    },
    async mounted() {
    },
    created() {
        bus.$on('customerLoaded', (leadTraveller) => {
            this.leadTraveller = leadTraveller
        })
        bus.$on('additionalTravellersLoaded', (travellers) => {
        })

    },
    computed: {
    },
    methods: {
        toggleActivities() {
            this.activated = !this.activated
        }
    }
}
</script>
