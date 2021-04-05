<template>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-1 dropdown-button">
                <button class="btn btn-link cardhead" @click="toggleTraveller">
                    Lead Traveller details {{lead_traveller? ": " + lead_traveller : ''}}
                </button>
            </h5>
        </div>
        <div class="card-body" v-if="showTraveller">
            <form action="/booking/createLeadTraveller" method="post">
                <h4>Your Details</h4>
                <div class="row">
                    <div class="col-sm-4 form-group field-separation">
                        <label type="form-label" for="first_name" v-show="first_name">First name</label>
                        <input type="text" v-model="first_name" placeholder="First name" name="first_name" class="form-control maxwidth" />                        
                    </div>
                    <div class="col-sm-4 form-group field-separation">
                        <label class="form-label" for="middle_name" v-show="middle_name">Middle name(s)</label>
                        <input type="text" v-model="middle_name" placeholder="Middle name" name="middle_name" class="form-control maxwidth" />
                    </div>
                    <div class="col-sm-4 form-group field-separation">
                        <label class="form-label" for="last_name" v-show="last_name">Last name</label>
                        <input type="text" v-model="last_name" placeholder="Last name" name="last_name" id="last_name" class="form-control maxwidth" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="email_address" v-show="email_address">E-mail</label>
                        <input type="email" v-model="email_address" placeholder="Email address" name="email_address" class="form-control" />
                        <label class="invalid" v-if="email_invalid">{{email_validation}}</label>
                        <label class="valid" v-else>{{email_validation}}</label>
                    </div>
                    <div class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="mobile_number" v-show="mobile_number">Mobile number</label>
                        <input type="text" v-model="mobile_number" placeholder="Mobile number" @change="validPhone" name="mobile_number" class="form-control" />
                        <label class="invalid" v-if="mobile_number_invalid">{{mobile_number_validation}}</label>
                        <label class="valid" v-else>{{mobile_number_validation}}</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group field-separation has-dropdown">
                        <select v-model="other_phone_number_type" name="additional_phone_number_select" class="dropdown">
                            <option value="" disabled selected>Additional Phone</option>
                            <option value="mobile">UK Mobile</option>
                            <option value="home">UK Home Phone</option>
                            <option value="business">UK Business Phone</option>
                            <option value="other">Non-UK Phone</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="other_phone_number" v-show="other_phone_number">{{otherNumberType}}</label>
                        <input type="text" v-model="other_phone_number" @change="validPhone" :placeholder="otherNumberType" name="other_phone_number" id="other_phone_number" class="form-control">
                        <label class="invalid" v-if="other_phone_number_invalid">{{other_phone_number_validation}}</label>
                        <label class="valid" v-else>{{other_phone_number_validation}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 form-group field-separation">
                        <label class="form-label" for="date_of_birth" >Date of Birth</label>
                        <input type="date" v-model="date_of_birth" name="date_of_birth" class="form-control" />
                    </div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-6 form-group field-separation lower">
                        <input class="form-check-input inset" 
                            @click="toggleSameAddress" 
                            type="checkbox" 
                            name="billing" 
                            value="true">
                        <label class="form-check-label inset" for="billing">
                            Delivery and billing address are the same
                        </label>
                    </div>
                </div>
                <div class="row">
                   <div class="col-sm-6 form-group field-separation">
                        <h4>Home or Delivery Address</h4>
                   </div>
                   <div class="col-sm-6 form-group field-separation" v-if="!same_address">
                        <h4>Billing Address (optional)</h4>
                   </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="adl1" v-show="address_line_1">Delivery Address line 1</label>
                        <input v-model="address_line_1" type="text" placeholder="Delivery Address Line 1" class="form-control">
                    </div>
                    <div v-if="!same_address" class="col-sm-6 form-group field-separation">
                        <label class="form-label" v-show="billing_address_line_1">Billing Address line 1</label>
                        <input type="text" v-model="billing_address_line_1" placeholder="Billing Address Line 1" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="adl1" v-show="address_line_2">Delivery Address line 2</label>
                        <input v-model="address_line_2" type="text" placeholder="Delivery Address Line 2" class="form-control">
                    </div>
                    <div v-if="!same_address" class="col-sm-6 form-group field-separation">
                        <label class="form-label" v-show="billing_address_line_2">Billing Address line 2</label>
                        <input type="text" v-model="billing_address_line_2" placeholder="Billing Address Line 2" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="adl1" v-show="address_line_3">Delivery Address line 3</label>
                        <input v-model="address_line_3" type="text" placeholder="Delivery Address Line 3" class="form-control">
                    </div>
                    <div v-if="!same_address" class="col-sm-6 form-group field-separation">
                        <label class="form-label" v-show="billing_address_line_1">Billing Address line 3</label>
                        <input type="text" v-model="billing_address_line_3" placeholder="Billing Address Line 3" class="form-control">
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="town" v-show="town">Town</label>
                        <input type="text" v-model="town" name="town" placeholder="Town" class="form-control">
                    </div>
                    <div v-if="!same_address" class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="billing_town" v-show="billing_town">Town</label>
                        <input type="text" v-model="billing_town" name="billing_town" placeholder="Town" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="county" v-show="county">County</label>
                        <input type="text" v-model="county" name="county" placeholder="County" class="form-control">
                    </div>
                    <div v-if="!same_address" class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="billing_county" v-show="billing_county">Billing County</label>
                        <input type="text" v-model="county" name="billing_county" placeholder="County" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="postcode" v-show="postcode">Postcode</label>
                        <input type="text" v-model="postcode" name="postcode" placeholder="Postcode" class="form-control">
                    </div>
                    <div v-if="!same_address" class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="billing_postcode" v-show="billing_postcode">Billing Postcode</label>
                        <input type="text" v-model="billing_postcode" name="billing_postcode" placeholder="Postcode" class="form-control">
                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="country" v-show="country">Country</label>
                        <input type="text" v-model="country" name="country" placeholder="Country" class="form-control">
                    </div>
                    <div v-if="!same_address" class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="billing_country" v-show="billing_country">Country</label>
                        <input type="text" v-model="billing_country" name="billing_country" placeholder="Country" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group field-separation">
                        <button v-if="validForm"
                            type="button"
                            :formId="formId"
                            class="btn btn-success"
                            @click="storeTraveller">Save Traveller</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: ['formId', 'tour', 'order_id'],
    mounted() {
        console.log('OTM Booking form loaded', this.order_id)
    },
    data() {
        return {
            showTraveller: false,
            lead_traveller: '',
            first_name: '',
            last_name: '',
            middle_name: '',
            email_address: '',
            mobile_number: '',
            address_line_1: '',
            address_line_2: '',
            address_line_3: '',
            country: '',
            county: '',
            town: '',
            postcode: '',
            billing_address_line_1: '',
            billing_address_line_2: '',
            billing_address_line_3: '',
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
            other_phone_number_invalid: false,
            other_phone_number_validation: 'Please enter a valid phone number'

        }
    },
    computed: {
        otherNumberType: function() {
            const name = this.other_phone_number_type
            return 'Other ' + name.charAt(0).toUpperCase() + name.slice(1) + ' number'
        },
        validForm: function() {
            return this.first_name.length && this.last_name.length && !this.mobile_number_invalid && this.date_of_birth
        }
    },
    methods: {
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
        storeTraveller() {
            console.log(this.tour)
            axios.post('/api/booking/lead-traveller', {
                tour: this.tour,
                order_id: this.order_id,
                form_id: this.formId,
                first_name: this.first_name,
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
                console.log('error', e)
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
    top: 2rem;
}
</style>
