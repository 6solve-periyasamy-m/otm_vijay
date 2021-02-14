<template>
<div class="col-12 col-sm-12">
    <h3>Total Cost of Travel Booking</h3>

    <div class="row">
        <div class="col-3 col-sm-6">
            4 Passengers        
        </div>
        <div class="col-4 col-sm-4">
            £1500 per passenger
        </div>
        <div class="col-3 col-sm-2">
            Total
        </div>
        <div class="col-2">
            $6000
        </div>
    </div>
    <div class="row">
        <div class="col-3 col-sm-6">
            Full payment now
        </div>
        <div class="col-4 col-sm-4">
            Discount £600
        </div>
        <div class="col-3 col-sm-2">
            £5400
        </div>
        <div class="col-2">
            <button>Pay Now</button>
        </div>
    </div>
    <div class="row">
        <div class="col-3 col-sm-6">
            
        </div>
        <div class="col-4 col-sm-4">
            Full payment discount
        </div>
        <div class="col-3 col-sm-2">
            £5400
        </div>
        <div class="col-2">
            <button>Pay Now</button>
        </div>
    </div>

    <hr/>
    <div class="row">
        <div class="col-0 col-sm-6">
            Alternatively, you can select an installment plan
        </div>
        <div class="col-8 col-sm-4">
            Installment plan
        </div>
        <div class="col-4 col-sm-2">
            <select name="installment_plan">
                <option>Select</option>
                <option>Plan 1</option>
                <option>Plan 2</option>
                <option>Plan 3</option>
            </select>
        </div>
    </div>
    <hr/>
    <div class="row">
        <h4>Payment Schedule and Installment Plan</h4>
        <payment-installments></payment-installments>
    </div>
    <hr/>

    <div class="row">
        <div class="col-0 col-sm-6">
            Date
        </div>
        <div class="col-8 col-sm-4">
            Total Paid
        </div>
        <div class="col-4 col-sm-2">
            £0.00
        </div>
    </div>

    <div class="row">
        <div class="col-0 col-sm-6">
            Date
        </div>
        <div class="col-8 col-sm-4">
            Balance remaining
        </div>
        <div class="col-4 col-sm-2">
            £5900.00 <button>Pay Now</button>
        </div>
    </div>


</div>
</template>
<script>
export default {
    data() {
        return {
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