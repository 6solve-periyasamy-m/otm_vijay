<template>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-1 dropdown-button">
                <button class="btn btn-link cardhead" @click="toggleTraveller">
                    Lead Traveller details
                </button>
            </h5>
        </div>
        <div class="card-body" v-if="showTraveller">
            <form action="/booking/createLeadTraveller" method="post">
                <div class="row">
                    <div class="col-sm-4 form-group height-rem5">
                            <label type="form-label" for="first_name" v-show="first_name">First name</label>
                            <input type="text" v-model="first_name" placeholder="First name" name="first_name" id="first_name" class="form-control maxwidth" />                        
                    </div>
                    <div class="col-sm-4 form-group height-rem5">
                        <label class="form-label" for="middle_name" v-show="middle_name">Middle name(s)</label>
                        <input type="text" v-model="middle_name" placeholder="Middle name" name="middle_name" id="middle_name" class="form-control maxwidth" />
                    </div>
                    <div class="col-sm-4 form-group height-rem5">
                        <label class="form-label" for="last_name" v-show="last_name">Last name</label>
                        <input type="text" v-model="last_name" placeholder="Last name" name="last_name" id="last_name" class="form-control maxwidth" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group height-rem5">
                        <label class="form-label" for="email_address" v-show="email_address">E-mail</label>
                        <input type="email" v-model="email_address" placeholder="Email address" name="email_address" id="email_address" class="form-control" />
                    </div>
                    <div class="col-sm-6 form-group height-rem5">
                        <label class="form-label" for="mobile_number" v-show="mobile_number">Mobile number</label>
                        <input type="text" v-model="mobile_number" placeholder="Mobile number" pattern="^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$" name="mobile_number" id="mobile_number" class="form-control" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <select v-model="other_phone_number_type" name="additional_phone_number_select" class="x-customdropdown">
                            <option value="" disabled selected>Additional Phone number</option>
                            <option value="(mob)">Mobile</option>
                            <option value="(tel)">Home</option>
                            <option value="(bus)">Business</option>
                        </select>

                        <input type="text" v-model="other_phone_number" @change="validphone" placeholder="Other contact number" name="other_phone_number" id="other_phone_number" class="form-control">
                        <label class="invalid" v-if="other_phone_number_invalid">{{other_phone_number_validation}}</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4 form-group height-rem5">
                        <label class="form-label" for="date_of_birth">Date of Birth
                        <input type="date" v-model="date_of_birth" name="date_of_birth" class="form-control"></label>
                    </div>
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-6">
                        <div class="customformcheck">
                            <input class="form-check-input" @click="toggleSameAddress" type="checkbox" name="billing" value="True" id="billing">
                            <label class="form-check-label" for="billing">
                                Delivery and billing address are the same
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h1>&nbsp;</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group height-rem5">
                        <label class="form-label" for="adl1" v-show="address_line_1">Delivery Address line 1</label>
                        <input v-model="address_line_1" type="text" class="form-control">
                    </div>
                    <div v-if="!same_address" class="col-sm-6 form-group height-rem5">
                        <label class="form-label" v-show="billing_address_line_1">Billing Address line 1</label>
                        <input type="text" v-model="billing_address_line_1" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group height-rem5">
                        <label class="form-label" for="adl1" v-show="address_line_2">Delivery Address line 2</label>
                        <input v-model="address_line_2" type="text" class="form-control">
                    </div>
                    <div v-if="!same_address" class="col-sm-6 form-group height-rem5">
                        <label class="form-label" v-show="billing_address_line_2">Billing Address line 2</label>
                        <input type="text" v-model="billing_address_line_2" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group height-rem5">
                        <label class="form-label" for="adl1" v-show="address_line_3">Delivery Address line 3</label>
                        <input v-model="address_line_3" type="text" class="form-control">
                    </div>
                    <div v-if="!same_address" class="col-sm-6 form-group height-rem5">
                        <label class="form-label" v-show="billing_address_line_1">Billing Address line 3</label>
                        <input type="text" v-model="billing_address_line_3" class="form-control">
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-6 form-group height-rem5">
                        <label class="form-label" for="town" v-show="town">Town</label>
                        <input type="text" v-model="town" name="town" id="town" class="form-control">
                    </div>
                    <div v-if="!same_address" class="col-sm-6 form-group height-rem5">
                        <label class="form-label" for="billing_town">Town</label>
                        <input type="text" v-model="billing_town" name="billing_town" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group height-rem5">
                        <label class="form-label" for="county">County</label>
                        <input type="text" v-model="county" name="county" class="form-control">
                    </div>
                    <div v-if="!same_address" class="col-sm-6 form-group height-rem5">
                        <label class="form-label" for="billing_county">Billing County</label>
                        <input type="text" v-model="county" name="billing_county" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group height-rem5">
                        <label class="form-label" for="postcode" v-show="postcode">Postcode</label>
                        <input type="text" v-model="postcode" name="postcode" class="form-control">
                    </div>
                    <div v-if="!same_address" class="col-sm-6 form-group height-rem5">
                        <label class="form-label" for="billing_postcode">Billing Postcode</label>
                        <input type="text" v-model="billing_postcode" name="billing_postcode" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group height-rem5">
                        <label class="form-label" for="country" v-show="country">Country</label>
                        <input type="text" v-model="country" name="country" class="form-control">
                    </div>
                    <div v-if="!same_address" class="col-sm-6 form-group height-rem5">
                        <label class="form-label" for="billing_country" v-show="billing_country">Country</label>
                        <input type="text" v-model="billing_country" name="billing_country" class="form-control">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script>
export default {
    mounted() {
        console.log('Component mounted.')
    },
    data() {
        return {
            showTraveller: false,
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
            other_phone_number: '',
            other_phone_number_input: '',
            other_phone_number_type: '',
            date_of_birth: '',
            same_address: false,
            other_phone_number_invalid: false,
            other_phone_number_validation: 'Please enter a valid phone number'

        }
    },
    // computed: {
    //     other_phone_number: function() {
    //         return this.other_phone_number_type + ' ' + this.other_phone_number_input
    //     }
    // },
    methods: {
        toggleTraveller() {
            this.showTraveller = !this.showTraveller
        },
        toggleSameAddress() {
            this.same_address = !this.same_address
        },
        validphone(e) {
            // re seems to work
            const re = /((\+44(\s\(0\)\s|\s0\s|\s)?)|0)7\d{3}(\s)?\d{6}/
            // re2 seems too permissive?
            const re2 = /^((((\(?0\d{4}\)?\s?\d{3}\s?\d{3})|(\(?0\d{3}\)?\s?\d{3}\s?\d{4})|(\(?0\d{2}\)?\s?\d{4}\s?\d{4}))(\s?\(\d{4}|\d{3}))?)|((\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3})|((((\+44\s?\d{4}|\(?0\d{4}\)?)\s?\d{3}\s?\d{3})|((\+44\s?\d{3}|\(?0\d{3}\)?)\s?\d{3}\s?\d{4})|((\+44\s?\d{2}|\(?0\d{2}\)?)\s?\d{4}\s?\d{4}))(\s?\(\d{4}|\d{3}))?$/

            const field = e.srcElement.name
            switch (field) {
                case 'other_phone_number':
                    this.other_phone_number_invalid = false
                    if (!this.other_phone_number.match(re)) {
                        this.other_phone_number_invalid = true
                        return false
                    }
                    break
                default:
                    alert(field + ' not handled in switch')
            }
            return true
        },
        storeData() {
            return true
        }
    }
}
</script>

<style lang="scss" scoped>
input[pattern]:invalid,
.invalid {
    color: red;
}
  .maxwidth {
    width: 100%;
  }
  .height-rem5 {
    min-height: 5rem;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
  }
</style>
