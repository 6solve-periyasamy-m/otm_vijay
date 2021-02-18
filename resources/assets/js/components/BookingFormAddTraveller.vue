<template>
<div class="container">
    <div class="ept" :id="additionalTraveller">
        <div class="ept-control">
            <button v-if="emptyForm" type="button" class="btn btn-warning" @click="removeTraveller">Remove Traveller</button>
            <div v-else>
                <label for="include">Include</label>
                <input type="checkbox" name="include" :value="additionalTraveller">
            </div>
        </div>
        <div class="ept-form">
            <div class="row">
                <div class="col-sm-6 form-group field-separation">
                    <label type="form-label" for="first_name" v-show="first_name">First name</label>
                    <input type="text" v-model="first_name" placeholder="First name" name="first_name" class="form-control maxwidth" />
                </div>
                <div class="col-sm-6 form-group field-separation">
                    <label type="form-label" for="last_name" v-show="last_name">Last name</label>
                    <input type="text" v-model="last_name" placeholder="Last name" name="last_name" class="form-control maxwidth" />
                </div>
            </div>


            <div class="row">
                <div class="col-sm-6 form-group field-separation">
                    <label class="form-label" for="email_address" v-show="email_address">E-mail</label>
                    <input type="email" v-model="email_address" placeholder="Email address" name="email_address" id="email_address" class="form-control" />
                </div>
                <div class="col-sm-6 form-group field-separation">
                    <label class="form-label" for="mobile_number" v-show="mobile_number">Mobile number</label>
                    <input type="text" v-model="mobile_number" placeholder="Mobile number" @change="validPhone" name="mobile_number" id="mobile_number" class="form-control" />
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3 form-group field-separation has-dropdown">
                    <select v-model="other_phone_number_type" name="additional_phone_number_select" class="dropdown">
                        <option value="" disabled selected>Additional Phone</option>
                        <option value="mobile">Other Mobile</option>
                        <option value="home">Other Home Phone</option>
                        <option value="business">Other Business Phone</option>
                    </select>
                </div>
                <div class="col-sm-6 form-group field-separation">
                    <label class="form-label" for="other_phone_number" v-show="other_phone_number">{{otherNumberType}}</label>
                    <input type="text" v-model="other_phone_number" @change="validphone" :placeholder="otherNumberType" name="other_phone_number" id="other_phone_number" class="form-control">
                    <label class="invalid" v-if="other_phone_number_invalid">{{other_phone_number_validation}}</label>
                </div>
                <div class="col-sm-3 form-group field-separation">
                    <label class="form-label" for="date_of_birth" >Date of Birth</label>
                    <input type="date" v-model="date_of_birth" name="date_of_birth" class="form-control" />
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props: ['formId'],
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                first_name: '',
                last_name: '',
                email_address: '',
                date_of_birth: '',
                mobile_number: '',
                other_phone_number: '',
                other_phone_number_invalid: false,
                other_phone_number_validation: 'Please enter a valid phone number'
            }
        },
        computed: {
            additionalTraveller: function() {
                return 'additionalTraveller-' + this.formId
            },
            emptyForm: function() {
                console.log('evaluation', this.first_name)
                return this.first_name == null || this.first_name == '' || this.first_name.length == 0;
            }
        },
        methods: {
            validPhone(e) {
                return true;
            }
        }
    }

</script>
