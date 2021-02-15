<template>
<div class="col-0 col-sm-12">
    <div class="row">
        <div :class="column_1">
        </div>
        <div :class="column_2">
            Schedule: {{ schedule.title }}
        </div>
        <div :class="column_3">
            {{ schedule.installments }} {{schedule.period}} installments
        </div>        
    </div>
    <div class="row">
        <div :class="column_1" class="payments__column--highlight">
            Date
        </div>
        <div :class="column_2" class="payments__column--highlight">
            Detail
        </div>
        <div :class="column_3" class="payments__column--highlight">
            Amount 
        </div>
        <div :class="column_4" class="payments__column--highlight">
            Action
        </div>
    </div>
    <div class="row">
        <div :class="column_1">
            Now
        </div>
        <div :class="column_2">
            Deposit
        </div>
        <div :class="column_3">
            £{{deposit_value}}
        </div>
        <div :class="column_4" v-if="status == 'new'">
            <button class="payments__button--action">Due Now</button>
        </div>
    </div>
    <div class="row" v-for="(installment,c) in installments" :key="c">
        <div :class="column_1">
            {{ schedule.period }} # {{ c+1 }}
        </div>
        <div :class="column_2">
            Installment
        </div>
        <div :class="column_3">
            {{currency}}{{ installment.amount }} 
        </div>
        <div :class="column_4">
            by {{ installment.due }}
        </div>
    </div>
</div>
</template>
<script>
import axios from 'axios';
import * as dateMath from 'date-arithmetic'
function paymentDates(date) {

}
export default {
    props: ['status', 'load_schedule', 'price'], 
    data() {
        return {
            months: [1,2,3],
            column_1: 'col-3',
            column_1x: 'col-6 col-offset-1',
            column_2: 'col-4',
            column_3: 'col-3',
            column_4: 'col-2',
            schedule: {title:'', period : '' },
            deposit_value: 0,
            period: null,
            currency: '£',
            installments: []

        }
    },
    async mounted() {
        console.log('before')
        const url = `/api/booking/payment-schedule/${this.load_schedule}`
        await axios.get(url)
                   .then(response => (this.schedule = response.data.schedule))
        console.log('after', this.schedule)
        this.deposit_value = this.calculate_deposit()
        this.installments = this.calculate_installments()
    },
    methods: {
        calculate_deposit() {
            const deposit_string = this.schedule.deposit
            let ri = 0
            var deposit_amount = 0;
            if (deposit_string.indexOf('%')>0) {
                ri = new Number(deposit_string.replace("%", ""));
                deposit_amount = (ri / 100) * this.price;
            } else {
                deposit_amount = new Number(deposit_string)
                console.log('deposit_amount',deposit_amount)
            }
            // console.log(deposit_string, ri, deposit_amount, deposit_string.indexOf('%'))
            return deposit_amount;
        },
        normalise_unit() {
                let unit;
                switch (this.schedule.period.toLowerCase()) {
                    case 'week':
                    case 'weeks':
                    case 'weekly':
                        unit = 'week'
                        break;
                    case 'bimonthly':
                        unit = 'month'
                        qty = 2
                        break;
                    case 'quarterly':
                        unit = 'month'
                        qty = 3
                        break;
                    case 'fortnightly':
                        unit = 'day'
                        qty = 2
                        break
                    default: 
                        unit = 'month'
                }
                return unit
        },
        calculate_installments() {
            //console.log('this.schedule.installments',this.schedule.installments)
            const installment_count = this.schedule.installments
            let repayments = this.price - this.deposit_value
            const payment = repayments / installment_count;

            const payment_numbers = new Array(installment_count).fill(payment);
            var previous_date = new Date

            const dueDates = payment_numbers.map((payment) => {
                let payment_date = new Date
                let qty = 1;
                const unit = this.normalise_unit()
                
                previous_date = dateMath.add(previous_date, 1, unit)
                return previous_date
            })
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            // turn two arrays into a useful array of objects
            const payments = payment_numbers.map((payment, i) => {
                return {
                    amount: payment,
                    sequence: i+1,
                    unit: this.normalise_unit(),
                    period: this.schedule.period,
                    due: dateMath.day(dueDates[i]) + '/' + monthNames[dateMath.month(dueDates[i])] + '/' + dateMath.year(dueDates[i])   
                }
            })
            console.log(payments) //, dueDates, repayments, this.period, this.installments)
            return payments
        }
    }    
}
</script>

<style scoped>
</style>