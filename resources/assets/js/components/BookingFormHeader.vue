<template>
<div class="container-fluid">
    <div class="row">
        <div class="bookingheader-container">
            <div class="bookingform-header__logo">
                <img class="small-logo logo" :src="logoPath" :alt="logoPath"/>
            </div>
            <div class="bookingform-header__title">
                <h2>Booking Form</h2>
                <h3 class="bookingform-header__title--main">{{company}}</h3>
                <h5 v-if="event != null">{{event.name}} <div v-if="tour != null">{{tour.name}}</div></h5> 
                <p>from {{ startDate(event) }} To {{endDate(event) }}</p>
            </div>
            <div class="bookingform-header__summary">
                <h6>Summary</h6>
                <p>Tour cost per person</p>
                <p>{{priceFormatter(tour.base_price_per_person)}}</p>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import dates from '../utilities'
export default {
    props: ['event', 'tour', 'company', 'logo', 'systemcurrency'],
    data() {
        return {
            debug: false,
            logoPath: '',
            tourcost: 0,
            currency: this.systemcurrency || 'GBP',
        }
    },
    mounted() {
        this.debug && console.log('Booking form header Component mounted.')
        if (this.logo.substr(0,1) !== '/') {
            this.logoPath = `/${this.logo}`
        } else {
            this.logoPath = this.logo
        }
    },
    methods: {
        startDate(event) {
            return dates.makeDateFromString(event.starts_at)
        },
        endDate(event) {
            return dates.makeDateFromString(event.ends_at)
        },
        priceFormatter(a) {
            const currency = this.currency
            let formatter = new Intl.NumberFormat('en-GB', {
                style: 'currency',
                currency: currency,
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            })
            return formatter.format(a)
        },
    }
}
</script>
<style lang="scss" scoped>
.small-logo {
    width: 120px;
    height: auto;
}
@media screen and (max-width: 482px) {
    .small-logo {
        width: 80px;
    }
    h2, h5, h4 {
        font-size: small;
    }
}
.img-fluid {
    width: 50vw;
    height: auto;
}
</style>
