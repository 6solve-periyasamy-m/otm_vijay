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
    <div class="row" v-for="month in months" :key="month">
        <div :class="column_1">
            Month {{ month }}
        </div>
        <div :class="column_2">
            Installment
        </div>
        <div :class="column_3">
            £1000 
        </div>
        <div :class="column_4">
            by 01/0{{3+month}}/2021
        </div>
    </div>

    <div class="row">
        <div :class="column_1">
            01/07/2021
        </div>
        <div :class="column_2">
            Balance remaining
        </div>
        <div :class="column_3">
            £5900.00 
        </div>
        <div :class="column_4" v-if="status == 'payment'">
            <button class="payments__button--action">Pay Now</button>
        </div>
    </div>
{{schedule}}
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
            installments: []

        }
    },
    async mounted() {
        console.log('before')
        const url = `/api/booking/payment-schedule/${this.load_schedule}`
        await axios.get(url)
                   .then(response => (this.schedule = response.data.schedule))
        console.log('after')
        this.deposit_value = this.calculate_deposit();
    },
    methods: {
        pc_value(s) {
            const re = /%d*/
            const t = re.exec(s)
            return parse_int(t)

        },
        calculate_deposit() {
            const deposit_string = this.schedule.deposit
            let ri = 0
            var deposit_amount = 0;
            if (deposit_string.indexOf('%')) {
                ri = new Number(deposit_string.replace("%", ""));
                deposit_amount = (ri / 100) * this.price;
            } else {
                deposit_amount = deposit_string.valueOf();
            }
            console.log(deposit_string, ri, deposit_amount)
            return deposit_amount;
        }
    }    
}
</script>

<style scoped>
</style>