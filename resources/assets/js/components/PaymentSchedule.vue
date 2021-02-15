<template>
<div class="col-12 col-sm-12">
    <h3>Total Cost of Travel Booking</h3>
    <div class="row">
        <div :class="column_1" class="payments__column--highlight">
            Total Charges
        </div>
        <div :class="column_2" class="payments__column--highlight">
            {{number_passengers}} Passengers        
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
            {{currency}}{{per_passenger}} per passenger
        </div>
        <div :class="column_2">
            {{currency}}{{total_charge}}
        </div>
        <div :class="column_3">
            {{currency}}{{discount}}
        </div>
        <div :class="column_4">
            {{currency}}{{pay_now_price}}            
        </div>
    </div>
    <hr/>
    <div class="row">
        <div :class="column_1x">
            Alternatively, you can select an installment plan...
        </div>

        <div :class="column_3">
            <select @change="changeSelection" v-model="schedule_selected" name="installment_plan">
                <option>Select</option>
                <option v-for="schedule in schedules" :key="schedule.id" :value="schedule.id">{{schedule.title}}</option>
            </select>
            <div class="message">
                {{ schedule_selected ? '' : 'Select a schedule for payments'}}
            </div>
        </div>
    </div>
    <hr/>
    <h4>Payment Schedule and Installment Plan</h4>
    <div class="row" v-if="schedule_selected">
        <payment-installments :key="changed" status="new" :price="total_price" :load_schedule="schedule_selected"></payment-installments>
    </div>
    <hr/>

    <div class="row">
        <div :class="column_1">
            
        </div>
        <div :class="column_2">
            Total Paid
        </div>
        <div :class="column_3">
            {{ paid }}
        </div>
        <div :class="column_4">
            
        </div>
    </div>

    <div class="row" v-if="paid > 0">
        <div :class="column_1">
            
        </div>
        <div :class="column_2">
            Balance remaining
        </div>
        <div :class="column_3">
            
        </div>
        <div :class="column_4" v-if="status == 'payment'">
            <button>Pay Now</button>
        </div>
    </div>
</div>
</template>
<script>
import axios from 'axios';

export default {
    props: ['status', 'tour', 'total_price', 'passengers', 'paid'],
    data() {
        return {
            column_1: 'col-3',
            column_1x: 'col-6 col-offset-1',
            column_2: 'col-4',
            column_3: 'col-3',
            column_4: 'col-2',
            schedules: [],
            schedule_selected: null,
            installments: [],
            changed: null,
            currency: '£',
            discount_rate: 10,
            total_charge: this.total_price,
            number_passengers: this.passengers
        }
    },
    computed: {
        per_passenger: function() {
            return this.total_charge / this.number_passengers
        },
        discount: function() {
            return this.total_charge * (this.discount_rate/100)
        },
        pay_now_price: function() {
            return this.total_charge - this.discount
        }
    },
    async mounted() {
        //console.log('Loading payment schedules for tour', this.tour, ' charge ', this.total_charge, ' passengers ', this.number_passengers)
        await axios.get(`/api/booking/payment-schedules`)
                   .then(response => (this.schedules = response.data.schedules))
    },
    methods: {
        changeSelection() {
            this.changed = this.schedule_selected;
        }
    }
}
</script>