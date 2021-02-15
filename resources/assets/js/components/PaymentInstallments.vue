<template>
<div class="col-0 col-sm-12">
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
            {{ period }} # {{ c+1 }}
        </div>
        <div :class="column_2">
            Installment
        </div>
        <div :class="column_3">
            {{currency}}{{ installment }} 
        </div>
        <div :class="column_4">
            by 'date_to_calc'
        </div>
    </div>
</div>
</template>
<script>
import axios from 'axios';
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
            schedule: null,
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
        this.calculate_installments()
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
            console.log(deposit_string, ri, deposit_amount, deposit_string.indexOf('%'))
            return deposit_amount;
        },
        calculate_installments() {
            console.log('this.schedule.installments',this.schedule.installments)
            const installment_count = this.schedule.installments
            this.period = this.schedule.period
            let repayments = this.price - this.deposit_value
            this.installments = new Array(installment_count).fill(repayments / installment_count);
            console.log(repayments, this.period, this.installments)
        }
    }    
}
</script>

<style scoped>
</style>