<template>
    <div class="controls">{{debug ? bookings : null}}
        <a class="controls-activation" @click="showControl=!showControl"> Controls </a>
        <div class="booking-form--control" v-show="showControl">
            
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
        showControl: false,
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
    const that = this
    bus.$on('setBookingToken', (current_token) => {
        // alert('CONTROL event: setBookingToken to '+ current_token)
        that.bookingToken = current_token
        // setCookie(that.token_label, that.bookingToken)
        // localStorage.active_token = that.bookingToken        // add current_token to the localStorage array of tokens
        // if (localStorage.tokens != undefined && localStorage.tokens.length > 0) {
        //   const localTokens = JSON.parse(localStorage.tokens)
        //   if (localTokens.indexOf(current_token) === -1) {
        //     localTokens.push(current_token)
        //     localStorage.tokens = JSON.stringify(localTokens)
        //   }
        // }
        // that.debug && console.log(`>>>> ${that.moduleName} CONTROL module: booking ${that.bookingToken}`)
        //that.findBookings(that.bookingToken)
        that.getBookings(current_token)
        // window.location.reload()
    })
    bus.$on('controlLoadBookings', () => {
      that.controlForms(that.showForms)
    })
  },
  // mounted() {
  //   //this.findBookings()
  // },
  methods: {
    // findBookings(current_token = null) {
    //   console.log('CONTROL: findBookings in localStorage: ',localStorage.getItem('tokens'))
    //   if (current_token == null && localStorage.getItem('tokens') == undefined || localStorage.getItem('tokens') == null) {
    //     return
    //   }
    //   // const tokens = JSON.parse(localStorage.getItem('tokens'))
    //   this.getBookings(current_token)
    //   // this.activeTokens = JSON.parse(localStorage.tokens)
    //   // const checkCurrent = this.activeTokens.filter(b => b.token==current_token)
    //   // console.log('CONTROL current_token', current_token)
    //   // console.log('CONTROL findBookings: check', this.activeTokens, checkCurrent)
    // },
    restoreActive() {
        if (!localStorage.active_token) {
          localStorage.active_token = this.bookingToken
        } 
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
            // load current bookings into localStore
            localStorage.setItem('tokens', JSON.stringify([]))
            console.log('recreating local', that.bookings)
            localStorage.setItem('tokens', JSON.stringify(that.bookings.map(b => b.token)))
            localStorage.setItem('active_token', token)
            //bus.$emit('setBookingToken', token)
          } else {
            console.log('not setting token as no data for it', token)
          }
        })
        .catch(error => {
          console.log('**** getting bookings error: ', error.response)
          if (error.response === 429) {
            alert('System is busy - please wait a minute and refresh')
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
      // alert('which booking is activating'+this.activateBooking)
      axios.get(`/api/booking/token/${this.activateBooking}`)
      .then(response => {
        console.log(response)
        const booking_url = response.data.booking_url
        window.location.href = `${booking_url}`
      })
      .catch(error => console.log(error))
      // window.location.reload(true)
    },
    // initialise form
    async initForm() {
        //alert('control init form??')
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
.controls-activation {
  margin-right: 1rem;
  display: inline-block;
}
</style>

