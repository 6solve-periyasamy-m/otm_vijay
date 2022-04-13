<template>
<div class="booking-container">
    <div class="card-options" v-if="!removed">
        <h3 v-if="developer">Additional Traveller Details for Order {{order_id}}</h3>
        <div class="ept-form additional" :id="form_id">
            <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
            <validation-message v-if="validationErrors"></validation-message>
            <div v-if="debug">
                {{edit_fields}} {{form_id}}
            </div>
            <div v-if="edit_fields">
                <div class="row">
                    <div class="col-sm-6 form-group field-separation">
                        <input type="email" v-model="email_address" placeholder="Email address" @change="validEmail" name="email_address" class="form-control" />
                        <label v-if="email_invalid" :class="{invalid: email_invalid}">{{email_validation}}</label>
                        <label v-else class="valid">Email address *</label>
                    </div>
                    <div class="col-sm-6 form-group field-separation">
                        <input required type="text" v-model="mobile_number" placeholder="Mobile number" @change="validPhone" name="mobile_number" class="form-control" />
                        <label :class="{invalid: mobileNumberInvalid}" v-if="mobile_number_invalid">{{mobile_number_validation}}</label>
                        <label class="valid" v-else>Mobile Number *</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group field-separation">
                        <select required @change="edit_fields = true" v-model="title" class="form-control form-select form-select-lg">
                            <option value="" default>Select a title</option>
                            <option value="Mr">Mr</option>
                            <option value="Ms">Ms</option>
                            <option value="Mrs">Mrs</option>
                            <option value="Miss">Miss</option>
                            <option value="Dr">Dr</option>
                            <option value="Prof">Prof</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group field-separation">
                        <label type="form-label" for="first_name" v-show="first_name">First name *</label>
                        <input required type="text" v-model="first_name" placeholder="First name" name="first_name" class="form-control maxwidth" />
                    </div>
                    <div class="col-md-3 form-group field-separation">
                        <label class="form-label" for="middle_names" v-show="middle_names">Middle name(s)</label>
                        <input type="text" v-model="middle_names" placeholder="Middle name" name="middle_names" class="form-control maxwidth" />
                    </div>
                    <div class="col-md-3 form-group field-separation">
                        <label class="form-label" for="last_name" v-show="last_name">Last name *</label>
                        <input required type="text" v-model="last_name" placeholder="Last name" name="last_name" class="form-control maxwidth" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group field-separation has-dropdown">
                        <select v-model="other_phone_number_type" name="additional_phone_number_select" class="dropdown">
                            <option value="" disabled selected>Additional Phone</option>
                            <option :value="{id: 'mobile', name: 'UK Mobile'}">UK Mobile</option>
                            <option :value="{id: 'home', name: 'UK Phone'}">UK Phone</option>
                            <option :value="{id: 'business', name: 'Business Phone'}">Business Phone</option>
                            <option :value="{id: 'other', name: 'Non UK Phone'}">Non UK Phone</option>
                        </select>
                        <label class="form-label" for="other_phone_number" v-show="other_phone_number">{{otherNumberType}}</label>
                        <input type="text" v-model="other_phone_number" @change="validPhone" :placeholder="otherNumberType" name="other_phone_number" class="form-control">
                        <label :class="{invalid: additionalNumberInvalid}" v-if="other_number_invalid">{{other_number_validation}}</label>
                        <label class="valid" v-else>{{other_phone_number_type}} Number</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group field-separation">
                        <label class="form-label required" for="date_of_birth">Date of Birth *</label>
                        <input type="date" v-model="date_of_birth" name="date_of_birth" class="form-control" />
                    </div>
                    <div class="col-sm-6 form-group field-separation">
                        <label class="form-label required" for="gender">Gender *</label>
                        <select v-model="gender" class="form-control">
                            <option default value="">Select gender</option>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group field-separation additional__buttons">
                        <button 
                            :disabled="!validForm && !latch"
                            type="button"
                            :formId="form_id"
                            class="btn"
                            :class="{'btn-default': !validForm,'btn-primary': validForm}"
                            @click="storeTraveller">Save Traveller</button>
                        <button class="btn btn-secondary" 
                            @click="removal=!removal">{{removal?'Disable':'Enable'}} removal</button>
                        <button v-if="removal" 
                            type="button"
                            :formId="form_id"
                            class="btn btn-warning"
                            @click="removeTraveller">Remove Traveller</button>
                    </div>
                </div>
                <div class="row" v-if="errors.length">
                Errors:
                    {{errors}}
                </div>
            </div>
            <div class="additional__name" v-else>
                <div class="additional__name--field">{{!title ? 'Add a new member of your travel group' : `${title} ${first_name} ${last_name}` }}</div>
                <div class="additional__name--controls">
                    <button class="btn btn-warning btn-edit" @click="edit_fields = true">Edit</button> 
                    <slot name="remove"></slot>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<style lang="scss" scoped>
    .additional {
        &__buttons {
            display: flex;
            flex-direction: row;
        }
        &__name {
            display: flex;
            flex-direction: row;
            flex: 1 1 25%;
            width: 100%;
            align-items: center;
            justify-content: flex-start;
            &--field {
                min-width: 50%;
            }
            &--controls {
                min-width: calc(50% - 10rem);
                .btn-edit {
                    &:hover {
                        background: blue;
                        color: yellow;
                    }
                }
            }
            padding: 0 0 0.25rem 0;
            margin: 0;
            border-bottom: thin #ddf solid;
        }

    }
</style>
<script>
import axios from 'axios'
import { bus } from '../bus'
import dates from '../utilities'
export default {
    props: ['traveller', 'tour', 'form_id', 'booking_token'],
    data() {
        return {
            debug: false,
            developer: false,
            moduleName: 'addTraveller',
            fields: [
                'customer_id',
                'title', 'first_name', 'middle_names', 'last_name', 
                'date_of_birth', 'gender', 'email_address',
                'mobile_number', 'other_phone_number', 'other_phone_number_type'
            ],
            title: '',
            first_name: '',
            middle_names: '',
            last_name: '',
            email_address: '',
            date_of_birth: '',
            gender: '',
            mobile_number: '',
            mobile_number_invalid: false,
            mobile_number_validation: 'Please enter a valid mobile number',
            other_phone_number: '',
            other_number_invalid: false,
            other_number_validation: 'Please enter a valid phone number',
            email_invalid: false,
            email_validation: 'If you register your Email Address we can retrieve your details',
            validphone: false,
            other_phone_number_type: '',
            otherNumberType: '',
            TravellerBooking: 'checked',
            customer: {},
            removed: false,
            removal: false,
            errors: [],
            edit_fields: false,
            validationMessage: '',
            validationErrors: '',
            validated: false,
            latch: false
        }
    },
    mounted() {
        this.moduleName = 'BookingFormAddTraveller'
        this.validationErrors = ''
        this.customer = this.traveller
        this.setCustomerFields()
        if (!this.traveller.id) {
            this.edit_fields = true
        }
        this.debug && console.log(`${this.moduleName} mounted for ${this.booking_token}, FormID:${this.form_id} Tour: ${this.tour}  Traveller: ${this.traveller.id}`)
    },
    created() {
        let that = this
        bus.$on('addTraveller', function(formId) {
            that.debug && console.log('BookingFormAddTraveller: setting', formId, that.form_id)
            if (formId === that.form_id) {
                console.log('BookingFormAddTraveller: form ' + that.form_id + ' edit activated')
                that.edit_fields = true
            }
        })
    },
    computed: {
        validForm: function () {
            this.debug && console.log('BookingFormAddTraveller: validForm called', this)
            return this.first_name.length && this.last_name.length && !this.mobile_number_invalid && this.date_of_birth;
        },
        mobileNumberInvalid: function () {
            return this.mobile_number.length > 1 && this.mobile_number_invalid
        },
        emailInvalid: function () {
            return this.email_address.length > 4 && this.email_invalid
        },
        additionalNumberInvalid: function () {
            return this.other_phone_number.length > 1 && this.other_number_invalid
        }
    },
    methods: {
        setCustomerFields() {
            let that = this
            this['id'] = this.customer['id']
            this.debug>5 && console.log('BookingFormAddTraveller: customer fields: ', that.customer)
            this.fields.forEach(function(key,value) {
                if (typeof that.customer[key] !== 'undefined') {
                    that[key] = that.customer[key]
                }
            })
            this.debug>5 && console.log('BookingFormAddTraveller: dob fields: ', that.date_of_birth)
            if (that.date_of_birth != undefined && that.date_of_birth != null) {
                that.date_of_birth = dates.isoString(that.date_of_birth)
            }
        },
        validPhone(e) {
            // valid_uk appears to be fairly accurate
            const valid_uk = /^\s*((?:[+](?:\s?\d)(?:[-\s]?\d)|0)?(?:\s?\d)(?:[-\s]?\d){9}|[(](?:\s?\d)(?:[-\s]?\d)+\s*[)](?:[-\s]?\d)+)\s*$/
            const field = e.srcElement.name
            this.debug>5 && console.log('BookingFormAddTraveller: validating ', field)
            switch (field) {
                case 'mobile_number':
                    if (!valid_uk.test(this.mobile_number)) {
                        this.mobile_number_invalid = true
                        this.debug>1 && console.log('BookingFormAddTraveller: Invalid!', this.mobile_number)
                        return false
                    }
                    this.mobile_number_invalid = false
                    break
                case 'other_phone_number':
                    if (this.other_phone_number_type !== 'other' && !valid_uk.test(this.other_phone_number)) {
                        this.other_number_invalid = true
                        return false
                    }
                    this.other_number_invalid = false
                    break
                default:
                    alert(field + ' not handled in switch')
            }
            this.debug>3 && console.log(field, 'BookingFormAddTraveller: validated')
            return true
        },
        validEmail() {
            let that = this
            if (!this.email_address.length) {
                return false
            }
            
            bus.$on('emailUsed', email => {
                alert('email already used')
                return false
            });
            bus.$emit('checkEmailUnique', this.email_address);
            const valid_email = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
            const valid = valid_email.test(this.email_address)
            this.email_invalid = !valid

            // is this an existing customer?
            axios.post('/api/booking/customer/email/check', {
                email_address: this.email_address
            })
                .then(response => {
                    that.debug>3 && console.log('BookingForm: email/check: ', response)
                    if (response.data.success) {
                        const customer = response.data.customer
                        that.title = customer.title
                        that.first_name = customer.first_name
                        that.middle_name = customer.middle_name
                        that.last_name = customer.last_name
                        that.mobile_number = customer.mobile_number
                        that.other_phone_number = customer.other_phone_number
                        that.gender = customer.gender
                        that.date_of_birth = dates.isoString(customer.date_of_birth)
                    }
                })
                .catch(error => {
                    console.log(error)
                })
            return false
        },
        storeTraveller() {
            const that = this
            this.latch = true
            that.debug>1 && console.log('BookingFormAddTraveller: store additional', that.booking_token)
            that.errors = []
            that.validationErrors = null
            axios.post('/api/booking/additional-traveller', {
                    booking_token: this.booking_token,
                    customer_id: this.customer_id,
                    title: this.title,
                    first_name: this.first_name,
                    middle_names: this.middle_names,
                    last_name: this.last_name,
                    gender: this.gender,
                    email_address: this.email_address,
                    mobile_number: this.mobile_number,
                    other_phone_number: this.other_phone_number,
                    other_phone_number_type: this.other_phone_number_type,
                    date_of_birth: this.date_of_birth
                })
                .then(response => {
                    that.debug>1 && console.log('BookingFormAddTraveller: additional traveller response', response.data.success, response.data)
                    if (response.data.success) {
                        that.include = 'checked'
                        that.edit_fields = false
                        that.debug>3 && console.log('BookingFormAddTraveller: stored', response)
                        const customer = response.data.customer

                        that.id = customer.id
                        that.title = customer.title
                        that.first_name = customer.first_name
                        that.middle_names = customer.middle_names
                        that.last_name = customer.last_name
                        that.email_address = customer.email_address
                        that.mobile_number = customer.mobile_number
                        that.other_phone_number = customer.other_phone_number
                        that.other_phone_number_type = customer.other_phone_number_type
                        that.date_of_birth = dates.isoString(customer.date_of_birth)
                        that.gender = customer.gender
                        that.validated = true
                        that.validationErrors = ''
                        that.traveller = customer
                        bus.$emit('AddedTraveler', customer)
                    } else {
                        that.validationMessage = 'Something did not appear to work correctly, please try again'
                    }
                })
                .catch(e => {
                    console.log('BookingFormAddTraveller: submit error', e)
                    that.errors.push(e.response.errors)
                    that.validationErrors = e.response.errors
                    that.validated = false
                })
        },
        removeTraveller() {
            const that = this
            this.debug && console.log('BookingFormAddTraveller: removing ', this.traveller)
            if (!this.traveller.id) {
                this.removed = true
                return
            }
            axios.post(`/api/booking/additional-traveller/remove`, {
                customer_id: this.traveller.id,
                booking_token: this.booking_token
            })
            .then(response => {
                that.removed = true
                console.log('removeTraveler', response, that.traveller)
                bus.$emit('removeTraveler', that.traveller)
            })
            .catch(error => console.log('BookingFormAddTraveller: remove customer error', error))            

            
        }
    }
}
</script>
