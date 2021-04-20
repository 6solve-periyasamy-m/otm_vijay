<template>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-1 dropdown-button">
                <button class="btn btn-link cardhead" @click="toggleTraveller">
                    Lead Traveller details {{lead_traveller? ": " + lead_traveller : '' }}
                    <div v-if="form_info">on order {{order_id}}</div>
                </button>
                
            </h5>
            <p class="caption" v-if="!lead_traveller && !showTraveller">Click here to start</p>
        </div>
        <div class="ept-form card-body" v-if="showTraveller">
            <h4>Your Details</h4>
            <div class="row">
                <div class="col-sm-2 form-group field-separation">
                    <select class="form-control">
                        <option value="" default>Title</option>
                        <option value="Mr">Mr</option>
                        <option value="Ms">Ms</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Miss">Miss</option>
                        <option value="Dr">Dr</option>
                        <option value="Prof">Prof</option>
                    </select>
                </div>
                <div class="col-sm-3 form-group field-separation">
                    <label type="form-label" for="first_name" v-show="first_name">First name</label>
                    <input type="text" v-model="first_name" placeholder="First name" name="first_name" class="form-control maxwidth" />
                </div>
                <div class="col-sm-3 form-group field-separation">
                    <label class="form-label" for="middle_names" v-show="middle_names">Middle name(s)</label>
                    <input type="text" v-model="middle_names" placeholder="Middle name" name="middle_names" class="form-control maxwidth" />
                </div>
                <div class="col-sm-3 form-group field-separation">
                    <label class="form-label" for="last_name" v-show="last_name">Last name</label>
                    <input type="text" v-model="last_name" placeholder="Last name" name="last_name" id="last_name" class="form-control maxwidth" />
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 form-group field-separation">
                    <label class="form-label" for="email_address" v-show="email_address">E-mail</label>
                    <input type="email" v-model="email_address" placeholder="Email address" @change="validEmail" name="email_address" class="form-control" />
                    <label v-if="email_invalid" :class="{invalid: email_invalid}">{{email_validation}}</label>
                    <label v-else class="valid">Email address</label>
                </div>
                <div class="col-sm-6 form-group field-separation">
                    <label class="form-label" for="mobile_number" v-show="mobile_number">Mobile number</label>
                    <input type="text" v-model="mobile_number" placeholder="Mobile number" @change="validPhone" name="mobile_number" class="form-control" />
                    <label :class="{invalid: mobile_number_invalid}" v-if="mobile_number_invalid">{{mobile_number_validation}}</label>
                    <label class="valid" v-else>{{mobile_number_validation}}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group field-separation has-dropdown">
                    <select v-model="other_phone_number_type" name="additional_phone_number_select" class="dropdown">
                        <option value="" disabled>Additional Phone</option>
                        <option :value="{id: 'mobile', name: 'UK Mobile'}">UK Mobile</option>
                        <option :value="{id: 'home', name: 'UK Phone'}">UK Phone</option>
                        <option :value="{id: 'business', name: 'Business Phone'}">Business Phone</option>
                        <option :value="{id: 'other', name: 'Non UK Phone'}">Non UK Phone</option>
                    </select>
                    <input type="text" v-model="other_phone_number" @change="validPhone" :placeholder="otherNumberType" name="other_phone_number" class="form-control">
                    <label :class="{invalid: other_number_invalid}" v-if="other_number_invalid">{{other_number_validation}}</label>
                    <label class="valid" v-else>{{other_phone_number_type.name}} Number</label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group field-separation">
                    <label class="form-label" for="date_of_birth">Date of Birth</label>
                    <input type="date" v-model="date_of_birth" name="date_of_birth" class="form-control" />
                </div>
            </div>
            <div class="row spacer">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Home/Delivery Address</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group field-separation">
                            <label class="form-label" for="adl1" v-show="address_line_1">Delivery Address line 1</label>
                            <input v-model="address_line_1" type="text" placeholder="Delivery Address Line 1" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group field-separation">
                            <label class="form-label" for="adl1" v-show="address_line_2">Delivery Address line 2</label>
                            <input v-model="address_line_2" type="text" placeholder="Delivery Address Line 2" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group field-separation">
                            <label class="form-label" for="adl1" v-show="address_line_3">Delivery Address line 3</label>
                            <input v-model="address_line_3" type="text" placeholder="Delivery Address Line 3" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 form-group field-separation">
                            <label class="form-label" for="town" v-show="town">Town</label>
                            <input type="text" v-model="town" name="town" placeholder="Town" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 form-group field-separation">
                            <label class="form-label" for="postcode" v-show="postcode">Postcode</label>
                            <input type="text" v-model="postcode" name="postcode" placeholder="Postcode" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 form-group field-separation">
                            <label class="form-label" for="country" v-show="country">Country</label>
                            <input type="text" v-model="country" name="country" placeholder="Country" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row spacer">
                <div class="col-sm-12">
                    <input class="form-check-input inset" @click="toggleSameAddress" type="checkbox" v-model="same_address">
                    <label class="form-check-label inset" for="billing">
                        Billing is delivery address
                    </label>
                </div>
            </div>
            <div class="row spacer">
                <div class="col-sm-6" v-if="!same_address">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Billing Address</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div v-if="!same_address" class="col-sm-12 form-group field-separation">
                            <label class="form-label" v-show="billing_line_1">Billing Address line 1</label>
                            <input type="text" v-model="billing_line_1" placeholder="Billing Address Line 1" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div v-if="!same_address" class="col-sm-12 form-group field-separation">
                            <label class="form-label" v-show="billing_line_2">Billing Address line 2</label>
                            <input type="text" v-model="billing_line_2" placeholder="Billing Address Line 2" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div v-if="!same_address" class="col-sm-12 form-group field-separation">
                            <label class="form-label" v-show="billing_line_3">Billing Address line 3</label>
                            <input type="text" v-model="billing_line_3" placeholder="Billing Address Line 3" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div v-if="!same_address" class="col-sm-8 form-group field-separation">
                            <label class="form-label" for="billing_town" v-show="billing_town">Town</label>
                            <input type="text" v-model="billing_town" name="billing_town" placeholder="Town" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div v-if="!same_address" class="col-sm-8 form-group field-separation">
                            <label class="form-label" for="billing_postcode" v-show="billing_postcode">Billing Postcode</label>
                            <input type="text" v-model="billing_postcode" name="billing_postcode" placeholder="Postcode" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div v-if="!same_address" class="col-sm-8 form-group field-separation">
                            <label class="form-label" for="billing_country" v-show="billing_country">Country</label>
                            <input type="text" v-model="billing_country" name="billing_country" placeholder="Country" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group field-separation">
                    <button v-if="validForm" type="button" class="btn btn-success" @click="storeTraveller">Save Traveller</button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import { bus, booking } from '../bus'
export default {
    props: ['form_info', 'order_id', 'customer', 'tour', 'booked'],
    async mounted() {
        this.debug && console.log('OTM Booking Lead Customer form loaded', this.order_id)
        bus.$on('customerLoaded', (customer) => {
            this.setCustomer(customer)
        })
    },
    data() {
        return {
            debug: false,
            showTraveller: false,
            lead_traveller: '',
            first_name: '',
            last_name: '',
            middle_names: '',
            email_address: '',
            mobile_number: '',
            address_line_1: '',
            address_line_2: '',
            address_line_3: '',
            country: '',
            county: '',
            town: '',
            postcode: '',
            billing_line_1: '',
            billing_line_2: '',
            billing_line_3: '',
            billing_country: '',
            billing_county: '',
            billing_town: '',
            billing_postcode: '',
            mobile_number: '',
            other_phone_number: '',
            other_phone_number_input: '',
            other_phone_number_type: '',
            date_of_birth: '',
            same_address: false,
            email_invalid: false,
            email_validation: 'Please enter a valid email address',
            mobile_number_invalid: false,
            mobile_number_validation: 'Please enter a valid phone number',
            other_number_invalid: false,
            other_number_validation: 'Please enter a valid phone number'.date_of_birth,
            fields: [
                'first_name', 'middle_names', 'last_name', 
                'date_of_birth', 'gender', 'email_address',
                'mobile_number', 'other_phone_number', 'other_phone_numnber_type',
                'address_line_1', 'address_line_2', 'address_line_3',
                'town', 'country', 'postcode',
                'billing_line_1', 'billing_line_2', 'billing_line_3',
                'billing_town', 'billing_country', 'billing_postcode',
            ],
            addressFields: [
                'address_line_1', 'address_line_2', 'address_line_3',
                'town', 'country', 'postcode'
            ]
        }
    },
    computed: {
        otherNumberType: function () {
            if (typeof this.other_phone_number_type.name == 'undefined') {
                return 'Additional Phone Number'
            }
            return 'Other ' + this.other_phone_number_type.name + ' number'
        },
        validForm: function () {
            return this.first_name.length && this.last_name.length && !this.mobile_number_invalid && this.date_of_birth
        }
    },
    methods: {
        setCustomer(customer) {
            this.debug && console.log('Lead Traveller customer', customer)
            let that = this
            this.fields.forEach(function(key,value) {
                //console.log(key, value, customer[key])
                if (customer[key]) {
                    that[key] = customer[key]
                }
            })
            let allSame = true
            this.addressFields.forEach(function(key,value) {
                let addresskey = key.replace('address_', '')
                let billingKey = `billing_${addresskey}`
                that.debug && console.log(key, billingKey, customer[key], customer[billingKey])
                if (customer[key] != customer[billingKey]) {
                    allSame = false
                }
            })
            this.debug && console.log('billing address matches', allSame)
            this.same_address = allSame
            this.lead_traveller = customer.first_name + ' ' + customer.last_name
        },
        toggleTraveller() {
            this.showTraveller = !this.showTraveller
        },
        toggleSameAddress() {
            this.same_address = !this.same_address
        },
        validPhone(e) {
            const valid_uk = /^\s*((?:[+](?:\s?\d)(?:[-\s]?\d)|0)?(?:\s?\d)(?:[-\s]?\d){9}|[(](?:\s?\d)(?:[-\s]?\d)+\s*[)](?:[-\s]?\d)+)\s*$/
            const field = e.srcElement.name
            switch (field) {
                case 'mobile_number':
                    this.mobile_number_invalid = false
                    if (!valid_uk.test(this.mobile_number)) {
                        this.mobile_number_invalid = true
                        return false
                    }
                    break
                case 'other_phone_number':
                    this.other_phone_number_invalid = false
                    if (!valid_uk.test(this.other_phone_number)) {
                        this.other_phone_number_invalid = true
                        return false
                    }
                    break
                default:
                    alert(field + ' not handled in switch')
            }
            return true
        },
        validEmail() {
            if (!this.email_address.length) {
                return false
            }
            const valid_email = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
            const valid = valid_email.test(this.email_address)
            this.debug && console.log(this.email, valid)
            this.email_invalid = !valid
            return false
        },
        storeTraveller() {
            this.debug && console.log('BookingFormLead.storeTraveller() tour:',this.tour)
            axios.post('/api/booking/lead-traveller', {
                    tour: this.tour,
                    order_id: this.order_id,
                    first_name: this.first_name,
                    middle_names: this.middle_names,
                    last_name: this.last_name,
                    email_address: this.email_address,
                    mobile_number: this.mobile_number,
                    other_phone_number: this.other_phone_number,
                    other_phone_number_type: this.other_phone_number_type,
                    date_of_birth: this.date_of_birth,
                    address_line_1: this.address_line_1,
                    address_line_2: this.address_line_2,
                    address_line_3: this.address_line_3,
                    country: this.country,
                    county: this.county,
                    town: this.town,
                    postcode: this.postcode,
                    billing_address_line_1: this.billing_address_line_1,
                    billing_address_line_2: this.billing_address_line_2,
                    billing_address_line_3: this.billing_address_line_3,
                    billing_country: this.billing_country,
                    billing_county: this.billing_county,
                    billing_town: this.billing_town,
                    billing_postcode: this.billing_postcode
                })
                .then(response => {
                    const customer = response.data.customer
                    this.lead_traveller = customer.first_name + ' ' + customer.last_name
                    this.showTraveller = false
                    // this.$emit('savedLeadCustomer', customer.first_name + ' ' + customer.last_name)
                })
                .catch(e => {
                    console.log('BookingFormLead.storeTraveller() error', e)
                })
        }
    }
}
</script>

<style lang="scss" scoped>
.inset {
    margin-left: 0.25rem;
    padding-left: 2rem;
}

.lower {
    position: relative;
    top: 1em;
}
</style>
