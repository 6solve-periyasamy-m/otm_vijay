<template>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-1 dropdownbutt">
                <button class="btn btn-link cardhead" @click="togglePayments">
                    Payment
                </button>
            </h5>
        </div>
        <div class="card-body" v-if="paymentsActive">
            <div class="row">
                <payment-schedule status="new" tour="1" :total_price="10000" :passengers="4"></payment-schedule>
            </div>
            <div class="row">
                <hr>
                <pdf></pdf>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import { bus } from '../bus'
import PaymentInstallments from './PaymentInstallments.vue'
export default {
    mounted() {
        let that = this
        bus.$on('debugOverride', (debug) => that.debug = debug)

        this.debug && console.log('Payments form active')
    },
    data() {
        return {
            debug: false,
            paymentsActive: false,
        }
    },
    methods: {
        togglePayments() {
            this.paymentsActive = !this.paymentsActive
        }
    }
}
</script>