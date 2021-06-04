<template>
<div class="container">
    <div class="ept" v-if="!removed">
        <h3 v-if="developer">Additional Traveller Details for Order {{order_id}} </h3>
        <div class="ept-form" :id="form_id">
            <div v-if="edit_fields">
                <div class="row">
                    <div class="col-sm-6 form-group field-separation">
                        <label type="form-label" for="first_name" v-show="first_name">First name</label>
                        <input type="text" v-model="first_name" placeholder="First name" name="first_name" class="form-control maxwidth" />
                        <label class="valid"></label>
                    </div>
                    <div class="col-sm-6 form-group field-separation">
                        <label type="form-label" for="last_name" v-show="last_name">Last name</label>
                        <input type="text" v-model="last_name" placeholder="Last name" name="last_name" class="form-control maxwidth" />
                        <label class="valid"></label>
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
                        <input type="text" v-model="mobile_number" placeholder="Mobile number" @change="validPhone" name="mobile_number" id="mobile_number" class="form-control" />
                        <label :class="{invalid: mobileNumberInvalid}" v-if="mobile_number_invalid">{{mobile_number_validation}}</label>
                        <label class="valid" v-else>Mobile Number</label>
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
                        <label class="valid" v-else>{{other_phone_number_type.name}} Number</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group field-separation">
                        <label class="form-label" for="date_of_birth">Date of Birth</label>
                        <input type="date" v-model="date_of_birth" name="date_of_birth" class="form-control" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10 form-group field-separation">
                        <button v-if="validForm" type="button" :formId="form_id" class="btn btn-secondary" @click="storeTraveller">Save Traveller</button>
                        <button v-if="emptyForm" type="button" :formId="form_id" class="btn btn-warning" @click="removeTraveller">Remove Traveller</button>
                    </div>
                </div>
                <div class="row" v-if="errors.length">
                Errors:
                    {{errors}}
                </div>
        
            </div>
            <div v-else>
                {{first_name}} {{last_name}} <button class="btn btn-small btn-warning" @click="edit_fields = true">Edit</button>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import {
    bus
} from '../bus'
export default {
    props: ['order_id', 'form_id', 'customer'],
    mounted() {
        this.setCustomerFields()
        this.debug && console.log('Additional traveller formId: ' + this.form_id, ' order '+this.order_id, ' customer ID' + this.customer_id)
    },
    created() {
        const that = this
        this.$on('addTraveller', function(formId) {
            console.log('adding', formId)
            that.edit_fields = true
        })
    },
    data() {
        return {
            debug: false,
            developer: false,
            fields: [
                'customer_id',
                'first_name', 'middle_names', 'last_name', 
                'date_of_birth', 'gender', 'email_address',
                'mobile_number', 'other_phone_number', 'other_phone_numnber_type'
            ],
            first_name: '',
            last_name: '',
            email_address: '',
            date_of_birth: '',
            mobile_number: '',
            mobile_number_invalid: false,
            mobile_number_validation: 'Please enter a valid mobile number',
            other_phone_number: '',
            other_number_invalid: false,
            other_number_validation: 'Please enter a valid phone number',
            email_invalid: false,
            email_validation: 'Please enter your email address',
            validphone: false,
            other_phone_number_type: '',
            otherNumberType: '',
            additionalTraveller: 'checked',
            removed: false,
            errors: [],
            edit_fields: false
        }
    },
    computed: {
        emptyForm: function () {
            // console.log('evaluation', this.first_name)
            return this.first_name == null || this.first_name == '' || this.first_name.length == 0;
        },
        validForm: function () {
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
            const that = this
            that['id'] = that.customer['id']
            this.fields.forEach(function(key,value) {
                if (that.customer[key]) {
                    that[key] = that.customer[key]
                }
            })
            this.edit_fields = false
        },
        validPhone(e) {
            // valid_uk appears to be fairly accurate
            const valid_uk = /^\s*((?:[+](?:\s?\d)(?:[-\s]?\d)|0)?(?:\s?\d)(?:[-\s]?\d){9}|[(](?:\s?\d)(?:[-\s]?\d)+\s*[)](?:[-\s]?\d)+)\s*$/
            const field = e.srcElement.name
            console.log('validating ', field)
            switch (field) {
                case 'mobile_number':
                    if (!valid_uk.test(this.mobile_number)) {
                        this.mobile_number_invalid = true
                        console.log('Invalid!', this.mobile_number)
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
            console.log(field, 'validated')
            return true
        },
        validEmail() {
            if (!this.email_address.length) {
                return false
            }
            const valid_email = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
            const valid = valid_email.test(this.email_address)
            //console.log(this.email_address, valid)
            this.email_invalid = !valid
            return false
        },
        storeTraveller() {
            const that = this
            axios.post('/api/booking/additional-traveller', {
                    form_id: this.form_id,
                    order_id: this.order_id,
                    customer_id: this.customer_id,
                    first_name: this.first_name,
                    last_name: this.last_name,
                    email_address: this.email_address,
                    mobile_number: this.mobile_number,
                    other_phone_number: this.other_phone_number,
                    other_phone_number_type: this.other_phone_number_type,
                    date_of_birth: this.date_of_birth
                })
                .then(response => {
                    that.include = 'checked'
                    that.edit_fields = false
                    that.debug && console.log('stored', response)
                })
                .catch(e => {
                    console.log('submit error', e)
                    that.errors.push(e)
                })
        },
        removeTraveller() {
            this.removed = true
            // this.$emit('remove', this.form_id) <- removed as looks wrong and no 'remove' event handler
            bus.$emit('removeTraveller', this.form_id)
        }
    }
}
</script>
