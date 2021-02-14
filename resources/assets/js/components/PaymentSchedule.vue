<template>
<div class="col-12 col-sm-12">
    <h3>Total Cost of Travel Booking</h3>
    <div class="row">
        <div :class="column_1" class="payments__column--highlight">
            4 Passengers        
        </div>
        <div :class="column_2" class="payments__column--highlight">
            Total Charges
        </div>
        <div :class="column_3" class="payments__column--highlight">
            Discount for full payment
        </div>
        <div :class="column_4" class="payments__column--highlight"> 
            <button class="payments__button--action">Pay Now</button>
        </div>
    </div>
    <div class="row">
        <div :class="column_1">
            £1500 per passenger
        </div>
        <div :class="column_2">
            £6000
        </div>
        <div :class="column_3">
            £600
        </div>
        <div :class="column_4">
            £5400            
        </div>
    </div>

    
    <hr/>
    <div class="row">
        <div :class="column_1x">
            Alternatively, you can select an installment plan
        </div>

        <div :class="column_3">
            <select name="installment_plan">
                <option>Select</option>
                <option>Plan 1</option>
                <option>Plan 2</option>
                <option>Plan 3</option>
            </select>
        </div>
    </div>
    <hr/>
    <h4>Payment Schedule and Installment Plan</h4>
    <div class="row">
        <payment-installments status="new"></payment-installments>
    </div>
    <hr/>

    <div class="row">
        <div :class="column_1">
            
        </div>
        <div :class="column_2">
            Total Paid
        </div>
        <div :class="column_3">
            £0.00
        </div>
        <div :class="column_4">
            
        </div>
    </div>

    <div class="row">
        <div :class="column_1">
            
        </div>
        <div :class="column_2">
            Balance remaining
        </div>
        <div :class="column_3">
            £5900.00 
        </div>
        <div :class="column_4" v-if="status == 'payment'">
            <button>Pay Now</button>
        </div>
    </div>


</div>
</template>
<script>
export default {
    props: ['status'],
    data() {
        return {
            column_1: 'col-3',
            column_1x: 'col-6 col-offset-1',
            column_2: 'col-4',
            column_3: 'col-3',
            column_4: 'col-2',
            schedule: [],
            installments: []
        }
    },
    methods: {
        loadPaymentSchedule() {

        },
        loadInstallments() {

        }
    }
}
</script>