<template>
<div class="container-fluid">
    <div class="row">
        <div class="bookingform-header">
            <div class="py-2 bookingform-header__logo">
                <img class="small-logo logo" :src="logoPath" :alt="logoPath"/>
            </div>
            <div class="bookingform-header__title">
                <h1 class="bookingform-header__title--main">{{company}} Booking Form</h1>
                <h2 v-if="event != null">{{event.name}}</h2>
                <h3 v-if="tour != null">{{tour.name}} <br/>from {{ startDate(event) }} To {{endDate(event) }}</h3>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import dates from '../utilities'
export default {
    props: ['event', 'tour', 'company', 'logo'],
    data() {
        return {
            debug: false,
            logoPath: ''
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
    }
}
</script>
<style lang="scss" scoped>
.small-logo {
    width: 150px;
    height: auto;
}
.img-fluid {
    width: 50vw;
    height: auto;
}
</style>
