<template>
    <div class="container">
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-1">
                    <button class="btn btn-link collapsed cardhead" @click="toggleAdditional">
                        Additional Travellers
                    </button>
                </h5>
            </div>
            <div id="collapseTwo">
                <div class="card-body" v-if="showAdditional">
                    <p>Please input data for any additional travellers that are accompanying you.</p>
                    <p>Use the add button to add more travellers or remove to delete entries.</p>
                    <div v-for="item in additional" :key="item.name">
                        <booking-form-add-traveller :form-id="formId"></booking-form-add-traveller>
                    </div>
                    <button type="button" class="btn btn-success" @click="addAdditional">Add another person</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                showAdditional: false,
                additional: [],
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        computed: {
            formId: function() {
                return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            }
        },
        methods: {
            toggleAdditional() {
                this.showAdditional = !this.showAdditional
            },
            addAdditional() {
                this.additional.push('<bookingform-addtemplate></bookingform-addtemplate>')
                this.showAdditional = true
                console.log('additional added')
            }
        }
    }
</script>
