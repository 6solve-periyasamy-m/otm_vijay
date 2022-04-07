<template>
    <div class="controls">{{debug ? bookings : null}}
        <div class="booking-form--control">
            <select  v-if="activeTokens" v-model="activateBooking" @change="activate">
              <option value="" disabled>Select a booking to load form</option>
              <option v-for="booking in bookings" :key="booking.token" :value="booking.token">{{booking.tour_name}} {{booking.name}}</option>
            </select>
            <button class="btn btn-sm btn-primary" @click="clearForm">Clear form</button>
        </div>
    </div>
</template>
<script>
// import { arrayBuffer } from 'stream/consumers'
import { bus } from '../bus'
import { setCookie, getCookie, deleteCookie } from '../cookies'
export default {
  props: ['tour', 'customer', 'token_label'],
  data() {
    return {
        debug: false,
        showControl: true,
        showForms: true,
        moduleName: 'BookingFormControl',
        bookingToken: null,
        activateBooking: String,
        activeTokens: [],
        bookings: [],
        login: true,
        auth: false
    }
  },
  created() {
    const that=this
    bus.$on('setBookingToken', (current_token) => {
        // alert('CONTROL event: setBookingToken to '+ current_token)
        that.bookingToken = current_token
        setCookie(that.token_label, that.bookingToken)
        localStorage.active_token = that.bookingToken
        const localTokens = JSON.parse(localStorage.tokens)
        if (localTokens.indexOf(current_token) === -1) {
          localTokens.push(that.bookingToken)
          localStorage.tokens = JSON.stringify(localTokens)
        }
        that.debug && console.log(`>>>> ${that.moduleName} CONTROL module: booking ${that.bookingToken}`)
        that.findBookings(that.bookingToken)
        // window.location.reload()
    })
    bus.$on('controlLoadBookings', () => {
      that.controlForms(that.showForms)
    })
  },
  mounted() {
    //this.findBookings()
  },
  methods: {
    findBookings(current_token = null) {

      if (current_token == null && localStorage.getItem('tokens') == undefined || localStorage.getItem('tokens') == null) {
        alert('CONTROL no booking yet')
        return
      }
      // const tokens = JSON.parse(localStorage.getItem('tokens'))
      this.getBookings(current_token)
      // this.activeTokens = JSON.parse(localStorage.tokens)
      // const checkCurrent = this.activeTokens.filter(b => b.token==current_token)
      // console.log('CONTROL current_token', current_token)
      // console.log('CONTROL findBookings: check', this.activeTokens, checkCurrent)
    },
    restoreActive() {
        if (localStorage.active_token) {
            this.getBookings(localStorage.active_token)
            setCookie(this.token_label, localStorage.active_token)
        } 
    },
    controlForms(show = false) {
      this.showForms = !this.showForms
    },
    // get bookings for this customer
    getBookings(token) {
      let that=this
      axios.get(`/api/booking/customer/bookings/${token}`)
        .then(response => {
          console.log('getBooking:',response.data)
          if (response.data.success && response.data.bookings != null && response.data.bookings.length) {
            that.bookings = response.data.bookings
            localStorage.setItem('tokens', JSON.stringify([]))

            console.log('recreating local', that.bookings)
            localStorage.setItem('tokens', JSON.stringify(that.bookings.map(b => b.token)))
          } else {
            console.log('not setting token as no data for it', token);
          }
        })
        .catch(error => {
          console.log('**** getting bookings error: ', error.response)
          console.log(error)
          if (error.response === 429) {
            alert('Too many requests - please refresh')
          }
        })
    },
    resetToken() {
      localStorage.active_token = this.activateBooking
      localStorage.setItem('tokens', JSON.stringify([this.activateBooking]));
  //alert('booking form CONTROL emit set token')
      bus.$emit('setBookingToken', this.activateBooking)
    },
    activate() {
  //alert('booking form CONTROL activiting token')
      console.log(`activating ${this.activateBooking}`)
      if (!this.activateBooking) {
        return
      }
      setCookie(this.token_label, this.activateBooking)
      bus.$emit('setBookingToken',this.activateBooking)
      window.location.reload(true)
    },
    // initialise form
    async initForm() {
alert('control init form??')
        bus.$emit('initialiseForm')
    },
    // remove cookie
    clearForm() {
      localStorage.setItem('tokens', JSON.stringify([]));
      deleteCookie(this.token_label)
      bus.$emit('resetBookingToken')
      window.location.reload(true)
    },
    storeActiveToken() {
      // localStorage.active_token[this.bookingToken] = 'active'
    }
  }
}
</script>
<style scoped lang="scss">
.card-header {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  input {
      border: none;
      padding: 0;
      font-size: small;
  }
}
</style>

