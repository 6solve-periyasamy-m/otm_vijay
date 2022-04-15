<template>
    <div class="booking-container">
        <div class="card card-options">
            <div class="card-header">
                <div v-if="!bookingToken" class="cookie_consent">
                    <h2>Cookies and Personal Data</h2>
                    <p>To make your booking using this form it is necessary to collect basic personal information 
                        and for you to easily retrieve this information. 
                        This is achieved using a secure browser session (locked with https:). 
                        As you may need to collect information from other travellers and may close this browser session
                        you will retain access to this form and your data via the browser cookie/local storage system.</p>
                    <p>You can use the Clear Forms option in controls at any time to remove this information.
                        If you then want to retrieve access to your bookings, when you re-enter your email address
                        we will (ask permission to send you an email with a one-time link to) reconnect you 
                        to your booking information.</p>
                    <p>The email will contain a link that will return you to this booking form and another link to clear all booking.
                        information from the system.</p>
                    <p>You can opt-out of this simply by finishing your booking, paying your deposit and clearing your data, 
                        or you can contact us to make your booking over the the phone.</p>
                    <p>If you wish to use our easy online booking form, please confirm your agreement with the above by ticking 
                        the box and pressing CONFIRM.</p> 
                    <label for="cookieAgreement">I agree </label>
                    <input type="checkbox" v-model="cookieAgreement">
                    <button :disabled="!cookieAgreement" @click="cookieAgreed" class="btn btn-small btn-warning">CONFIRM</button>
                </div>
                <div v-else>
                    <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
                    <h5 class="dropdown-button">
                        <p v-if="!bookingToken && !show_traveller">Start your booking by entering your details</p>
                        <button class="btn btn-link cardhead" @click="toggleTraveller">
                            <font-awesome-icon icon="book-reader" />
                            Lead Traveller details
                        </button>
                    </h5>
                </div>
                <div class="card-info" v-if="!show_traveller">
                    <div v-if="!bookingToken && noUser">
                        <p>
                            <font-awesome-icon icon="arrow-right" /> If you have a booking in progress, try entering your email address
                        </p>
                        <input v-model="email" type="email" placeholder="Retrieve booking by email" />
                        <div v-if="!getPassword">
                            <button v-if="email && !activeUser" @click="retrieveUser()" class="btn btn-primary">
                                    <font-awesome-icon icon="check" /> Check 
                            </button>
                        </div>
                    </div>
                    <div v-if="noUser">
                        <p v-if="!email_address">Please enter the Lead Traveller details</p>
                    </div>
                    <div v-if="!noUser">
                        <p v-if="email_address">Welcome {{first_name}}</p>
                    </div>
                    <div v-if="!bookingToken && activeUser">
                        <p>Active User</p>
                        <label for="password">Enter your password</label>
                        <input type="password" v-model="password">
                        <button @click="loginUser" class="btn btn-primary">
                            <font-awesome-icon icon="check" /> Login
                        </button>
                    </div>
                </div>
                <div v-if="bookingToken && !show_traveller">
                    <p class="caption">
                        <font-awesome-icon icon="arrow-right" /> Please fill in all sections
                    </p>
                    <div v-if="!bookingToken">
                        You have {{activeBookings}} bookings active. To access bookings, you must <a :href="loginLink">login</a>.
                    </div>
                </div>
                {{debug ? 'DEBUG MODE: Order retrieved by cookie: bookingToken: '+ bookingToken : ''}}
            </div>
            <div class="card-body" v-if="show_traveller">
                <div class="booking-container">
                    <div class="card-options">
                        <div class="ept-form">
                            <h4>Your Details</h4>
                            <div class="row">
                                <div class="col-md-3 form-group field-separation">
                                    <select v-model="title" class="form-control form-select form-select-lg">
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
                                    <label class="form-label" for="first_name" v-show="first_name">First name</label>
                                    <input type="text" v-model="first_name" placeholder="First name" name="first_name" class="form-control maxwidth" />
                                </div>
                                <div class="col-md-3 form-group field-separation">
                                    <label class="form-label" for="middle_names" v-show="middle_names">Middle name(s)</label>
                                    <input type="text" v-model="middle_names" placeholder="Middle name" name="middle_names" class="form-control maxwidth" />
                                </div>
                                <div class="col-md-3 form-group field-separation">
                                    <label class="form-label" for="last_name" v-show="last_name">Last name</label>
                                    <input type="text" v-model="last_name" placeholder="Last name" name="last_name" class="form-control maxwidth" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 form-group field-separation">
                                    <label class="form-label" for="email_address">Email address</label>
                                    <input type="email" v-model="email_address" placeholder="Email address" @change="validEmail" name="email_address" class="form-control" />
                                    <label v-if="email_invalid" :class="{invalid: email_invalid}">{{email_validation}}</label>
                                    
                                </div>
                                <div class="col-sm-6 form-group field-separation">
                                    <label class="form-label" for="mobile_number" v-show="mobile_number">Mobile number</label>
                                    <input type="text" v-model="mobile_number" placeholder="Mobile number" @change="validPhone" name="mobile_number" class="form-control" />
                                    <label v-if="mobile_number_invalid" :class="{invalid: mobile_number_invalid}">{{mobile_number_validation}}</label>
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
                                    <input type="date" v-model="date_of_birth" class="form-control" />
                                </div>
                                <div class="col-sm-6 form-group field-separation">
                                    <label class="form-label" for="gender">Gender</label>
                                    <select name="gender" v-model="gender" class="dropdown">
                                            <option default disabled value="">Select Gender</option>
                                            <option>Male</option>
                                            <option>Female</option>
                                        </select>
                                </div>
                            </div>
                            <div class="row spacer">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h4>Home/Home Address</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 form-group field-separation">
                                            <label class="form-label" for="adl1" v-show="address_line_1">Home Address line 1</label>
                                            <input v-model="address_line_1" type="text" placeholder="Home Address Line 1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 form-group field-separation">
                                            <label class="form-label" for="adl1" v-show="address_line_2">Home Address line 2</label>
                                            <input v-model="address_line_2" type="text" placeholder="Home Address Line 2" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 form-group field-separation">
                                            <label class="form-label" for="adl1" v-show="address_line_3">Home Address line 3</label>
                                            <input v-model="address_line_3" type="text" placeholder="Home Address Line 3" class="form-control">
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
                                            <label class="form-label" for="region" v-show="region">Region</label>
                                            <input type="text" v-model="region" name="region" placeholder="Region" class="form-control">
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
                                            <label class="form-label" for="country">Country</label>
                                            <select name="country" class="dropdown" v-model="country_id" :key="country.id" @change="validCountry">
                                                <option disabled value="">Select country</option>
                                                <option default value="67">United Kingdom</option>
                                                <option v-for="c in countries" :name="c.name" :value="c.id">{{c.name}}</option>
                                            </select>
                                            <div v-if="country_id==0">Please select a country</div>
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
                            <div class="row spacer" v-if="!same_address">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h4>Billing Address</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 form-group field-separation">
                                            <label class="form-label" v-show="billing_address_line_1">Billing Address line 1</label>
                                            <input type="text" v-model="billing_address_line_1" placeholder="Billing Address Line 1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 form-group field-separation">
                                            <label class="form-label" v-show="billing_address_line_2">Billing Address line 2</label>
                                            <input type="text" v-model="billing_address_line_2" placeholder="Billing Address Line 2" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 form-group field-separation">
                                            <label class="form-label" v-show="billing_address_line_3">Billing Address line 3</label>
                                            <input type="text" v-model="billing_address_line_3" placeholder="Billing Address Line 3" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8 form-group field-separation">
                                            <label class="form-label" for="billing_town" v-show="billing_town">Town</label>
                                            <input type="text" v-model="billing_town" name="billing_town" placeholder="Town" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8 form-group field-separation">
                                            <label class="form-label" for="billing_region" v-show="billing_region">Region</label>
                                            <input type="text" v-model="billing_region" name="billing_region" placeholder="Region" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8 form-group field-separation">
                                            <label class="form-label" for="billing_postcode" v-show="billing_postcode">Billing Postcode</label>
                                            <input type="text" v-model="billing_postcode" name="billing_postcode" placeholder="Postcode" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8 form-group field-separation">
                                            <label class="form-label" for="billing_country" v-show="billing_country">Billing Country</label>
                                            <select name="billing_country" class="dropdown" v-model="billing_country" :key="billing_country.id">
                                                <option default disabled value="">Select country</option>
                                                <option v-for="c in countries" :name="c.name" :value="c.id">{{c.name}}</option>
                                            </select>
                                        </div>
                                        <label class="form-label" for="billing_country" v-show="billing_country">Country</label>
                                        <input type="text" v-model="billing_country_id" name="billing_country" placeholder="Country" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group field-separation">
                                <button :disabled="!validForm" type="button" class="btn btn-primary" @click="storeTraveller">Save Traveller</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { bus } from '../bus'
import dates from '../utilities'
// TODO: incorporate a better way to show validation errors
// import ValidationErrors from './ValidationErrors.vue'
export default {
    props: ["booked", "tour"],
    data() {
        return {
            debug: false,
            bookingToken: null,
            moduleName: "leadTraveller",
            countries: [],
            email: "",
            password: "",
            auth: false,
            activeUser: false,
            noUser: false,
            getPassword: false,
            show_traveller: false,
            loginLink: "",
            activeBookings: 0,
            title: "",
            first_name: "",
            last_name: "",
            middle_names: "",
            full_name: "",
            email_address: "",
            mobile_number: "",
            address_line_1: "",
            address_line_2: "",
            address_line_3: "",
            country: "",
            country_id: 0,
            countries: [],
            region: "",
            town: "",
            postcode: "",
            billing_address_line_1: "",
            billing_address_line_2: "",
            billing_address_line_3: "",
            billing_country: "",
            billing_region: "",
            billing_town: "",
            billing_postcode: "",
            billing_country_id: 0,
            mobile_number: "",
            other_phone_number: "",
            other_phone_number_input: "",
            other_phone_number_type: "",
            date_of_birth: "",
            gender: "",
            same_address: false,
            email_invalid: false,
            email_validation: "Please enter a valid email address",
            mobile_number_invalid: false,
            mobile_number_validation: "Please enter a valid phone number",
            other_number_invalid: false,
            other_number_validation: "Please enter a valid phone number",
            fields: [
                "title",
                "first_name",
                "middle_names",
                "last_name",
                "date_of_birth",
                "gender",
                "email_address",
                "mobile_number",
                "other_phone_number",
                "other_phone_numnber_type",
                "same_address"
            ],
            billingAddressFields: [
                "name",
                "billing_address_line_1",
                "billing_address_line_2",
                "billing_address_line_3",
                "billing_town",
                "billing_region",
                "billing_country_id",
                "billing_postcode",
            ],
            homeAddressFields: [
                "name",
                "address_line_1",
                "address_line_2",
                "address_line_3",
                "town",
                "region",
                "country_id",
                "postcode"
            ],
            validationErrors: "",
            cookieAgreement: false
        };
    },
    async created() {
        let that = this;
        bus.$on("setBookingToken", token => {
            that.bookingToken = token;
            // this looks irrelevant: retest without it
            // let tokens
            // if (localStorage.tokens == undefined) {
            //     tokens = new Array()
            // } else {
            //     tokens = JSON.parse(localStorage.tokens)
            // }
            // that.debug && console.log(`${that.moduleName} created: booking ${that.bookingToken} : tokens`, tokens)
            // tokens.push(that.bookingToken)
            // localStorage.tokens = JSON.stringify(tokens)
            // localStorage.active_token = that.bookingToken
        });
        bus.$on("leadTravellerLoaded", (customer) => {
            that.setCustomer(customer);
            that.email = that.email_address;
            that.date_of_birth = dates.isoString(customer.date_of_birth);
            bus.$emit("setLeadTraveller", customer);
        });
        bus.$on("homeAddressLoaded", home_address => {
            that.debug > 1 && console.log("BookingFormLead: home address:", home_address);
            that.address_line_1 = home_address.address_line_1;
            that.address_line_2 = home_address.address_line_2;
            that.address_line_3 = home_address.address_line_3;
            that.town = home_address.town;
            that.region = home_address.region;
            that.postcode = home_address.postcode;
            that.country_id = home_address.country_id;
        });
        bus.$on("billingAddressLoaded", billing_address => {
            that.billing_address_line_1 = billing_address.address_line_1;
            that.billing_address_line_2 = billing_address.address_line_2;
            that.billing_address_line_3 = billing_address.address_line_3;
            that.billing_town = billing_address.town;
            that.billing_region = billing_address.region;
            that.billing_postcode = billing_address.postcode;
            that.billing_country_id = billing_address.country_id;
        });
        that.debug > 1 && console.log(`${this.moduleName} created`);
    },
    mounted() {
        let that = this;
        this.validationErrors = "";
        bus.$on("debugOverride", (debug) => that.debug = debug);
        if (that.tour != null) {
            this.loginLink = `/login?cb=${that.tour.url}`;
        }
        this.activeBookings = "";
        this.loadCountries();
        that.debug > 1 && console.log(`${this.moduleName} mounted Tour: ${this.tour.name}`);
    },
    computed: {
        otherNumberType: function () {
            if (typeof this.other_phone_number_type.name == "undefined") {
                return "Additional Phone Number";
            }
            return "Other " + this.other_phone_number_type.name + " number";
        },
        validForm: function () {
            return this.first_name.length && this.last_name.length && !this.mobile_number_invalid && this.date_of_birth && this.country_id;
        }
    },
    methods: {
        cookieAgreed() {
            if (this.cookieAgreement) {
                bus.$emit("resetBookingToken");
            }
        },
        base64(arg) {
            return Buffer.from(`${arg}`, "utf8").toString("base64");
        },
        /**
         * the lead booker has a customer account which can be retrieved by login/password
         * a salt token is requested from the server to encrypt login credentials
         * NB: this is a PoC currently, not very secure
         * TODO: Route to login using Laravel (not JS)
         */
        loginUser() {
            let that = this;
            const username = this.email;
            const password = this.password;
            const t = new Date();
            alert("TEST: login user");
            // request a salt value from the server which is then used in the encryption
            axios.get(`/api/booking/auth/token/${username}`)
                .then(response => {
                const salt = response.data.auth;
                let token = this.base64(`${salt}:${password}`);
                const url = "/api/booking/authenticate/user";
                const data = this.email;
                axios.post(url, data, {
                    headers: {
                        "Authorization": `Basic ${token}`
                    },
                })
                    .then(response => {
                    that.auth = false;
                    that.debug > 1 && console.log("BookingFormLead: authorised", response);
                    if (response.authorised) {
                        that.auth = true;
                    }
                })
                    .catch(error => {
                    console.log("auth error", error);
                });
            })
                .catch(error => {
                console.log("can not obtain token");
                return;
            });
        },
        validCountry() {
            if (this.country_id == 0) {
                return false;
            }
        },
        validEmail() {
            if (!this.email_address.length) {
                return false;
            }
            const valid_email = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            const valid = valid_email.test(this.email_address);
            // this.debug && console.log(this.email_address, valid)
            this.email_invalid = !valid;
            if (this.email && this.email_address !== this.email) {
                alert("You can not change your email address in this form, if you need to do that, please contact support");
                this.email_address = this.email;
            }
            else {
                this.retrieveUser();
            }
        },
        retrieveUser() {
            // if the cookie does not retrieve an active order
            // see if email address is registered (email a tokenised link)
            let that = this;
            // is it a registered user?
            if (!this.auth) {
                this.debug > 1 && console.log("BookingFormLead: checking for auth user");
                axios.get(`/api/booking/email/registered/${this.email_address}`)
                    .then(response => {
                    that.debug > 1 && console.log("BookingFormLead: email registered? response", response);
                    that.activeUser = response.data.existing;
                    const customer = response.data.customer;
                    if (that.activeUser) {
                        that.bookingToken = response.data.token;
                        that.show_traveller = true;
                        console.log("active user", that.activeUser);
                        that.setCustomer(customer);
                        bus.$emit("setBookingToken", that.bookingToken);
                        bus.$emit("retrieveUserData", that.bookingToken);
                        bus.$emit("controlLoadBookings");
                        alert("Your active booking data is available, please check your details and Save Traveller");
                    }
                    else {
                        that.noUser = true;
                    }
                })
                    .catch(error => console.log(error));
            }
            else {
                alert("You have been authenticated");
                this.debug > 1 && console.log("BookingFormLead: Authenticated user", that.customer);
            }
        },
        setCustomer(customer) {
            let that = this;
            this.fields.forEach(function (key, value) {
                if (customer[key]) {
                    that[key] = customer[key];
                }
            });
            if (customer.homeAddressFields) {
                customer.homeAddressFields.map(field => {
                    field = customer.homeAddressFields.field;
                });
            }
            if (customer.businessAddressFields) {
                customer.businessAddressFields.map(field => {
                    field = customer.businessAddressFields.field;
                });
            }
            that.full_name = customer.first_name + " " + customer.last_name;
            that.date_of_birth = dates.isoString(customer.date_of_birth);
        },
        toggleTraveller() {
            this.show_traveller = !this.show_traveller;
        },
        toggleSameAddress() {
            this.same_address = !this.same_address;
        },
        validPhone(e) {
            const valid_uk = /^\s*((?:[+](?:\s?\d)(?:[-\s]?\d)|0)?(?:\s?\d)(?:[-\s]?\d){9}|[(](?:\s?\d)(?:[-\s]?\d)+\s*[)](?:[-\s]?\d)+)\s*$/;
            const field = e.srcElement.name;
            switch (field) {
                case "mobile_number":
                    this.mobile_number_invalid = false;
                    if (!valid_uk.test(this.mobile_number)) {
                        this.mobile_number_invalid = true;
                        return false;
                    }
                    break;
                case "other_phone_number":
                    this.other_phone_number_invalid = false;
                    if (!valid_uk.test(this.other_phone_number)) {
                        this.other_phone_number_invalid = true;
                        return false;
                    }
                    break;
                default:
                    alert(field + " not handled in switch");
            }
            return true;
        },
        createBooking(customer_id, tour_id) {
            let that = this;
            axios.post("/api/booking/create-booking", {
                customer_id: customer_id,
                tour_id: tour_id,
                token: this.bookingToken,
                fullname: this.full_name
            })
                .then(response => {
                const booking = response.data.booking;
                // set the booking in each module
                that.debug && console.log("Lead creatingBooking", booking);
                bus.$emit("setBookingToken", booking.token);
                bus.$emit("bookingCreated", booking);
            })
                .catch(error => {
                console.log("error createBooking", error);
            });
        },
        loadCountries() {
            let that = this;
            axios.get("/api/booking/countries")
                .then(response => {
                if (response.data.success) {
                    that.countries = response.data.countries;
                }
                else {
                    that.countries = { id: 1, name: "United Kingdom", code: "UK", currency: "GBP" };
                    console.log("BookingFormLead: Country list: ", response);
                }
            })
                .catch(error => {
                console.log(error);
            });
        },
        storeTraveller() {
            let that = this;
            that.debug && console.log("BOOKING: storeTraveller", this.email_address);
            axios.post("/api/booking/lead-traveller", {
                bookingToken: this.bookingToken,
                title: this.title,
                first_name: this.first_name,
                middle_names: this.middle_names,
                last_name: this.last_name,
                email_address: this.email_address,
                mobile_number: this.mobile_number,
                other_phone_number: this.other_phone_number,
                other_phone_number_type: this.other_phone_number_type,
                date_of_birth: this.date_of_birth,
                gender: this.gender,
                address_line_1: this.address_line_1,
                address_line_2: this.address_line_2,
                address_line_3: this.address_line_3,
                country_id: this.country_id,
                region: this.region,
                town: this.town,
                postcode: this.postcode,
                same_address: this.same_address,
                billing_address_line_1: this.billing_address_line_1,
                billing_address_line_2: this.billing_address_line_2,
                billing_address_line_3: this.billing_address_line_3,
                billing_country_id: this.billing_country_id,
                billing_region: this.billing_region,
                billing_town: this.billing_town,
                billing_postcode: this.billing_postcode,
                tour: this.tour
            })
            .then(response => {
                that.debug && console.log("*** Lead Traveller customerStored, reponse", response);
                const customer = response.data.customer;
                that.full_name = customer.first_name + " " + customer.last_name;
                that.show_traveller = false;
                that.validationErrors = null;
                that.createBooking(customer.id, that.tour.id);

                that.debug && console.log('***** LeadTraveller emitting set with customer', customer)
                bus.$emit("setLeadTraveller", customer);
            })
            .catch(e => {
                console.log("BookingFormLead.storeTraveller() error", e);
                that.validationErrors = e.response.data.errors;
            });
        }
    },
}
</script>

<style lang="scss" scoped>
button[disabled] {
    background: grey;
    color: white;
}
.cookie_consent {
    opacity: 1;
    animation-name: fadeInOpacity;
    animation-iteration-count: 1;
    animation-timing-function: ease-in;
    animation-duration: 1s;
}
@keyframes fadeInOpacity {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}
.inset {
    margin-left: 0.25rem;
    padding-left: 2rem;
}

.lower {
    position: relative;
    top: 1em;
}
.btn-small {
    padding: 0.25rem;
}
</style>

