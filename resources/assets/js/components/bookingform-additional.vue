<template>
    <div class="container">
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-1">
                    <button class="btn btn-link collapsed cardhead" data-toggle="collapse" data-target="#collapseTwo"
                        aria-expanded="false" aria-controls="collapseTwo">
                        Additional Travellers
                    </button>
                </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    <p>Please input data for any additional travellers that are accompanying you.</p>
                    <p>Use the add button to add more travellers or remove to delete entries.</p>
                    <br>
                </div>
                <bookingformaddtemplate />
                <br>
                <button type="button" class="btn btn-success" id="addrow">Add</button>
            </div>
        </div>
    </div>
</template>

<script>
import bookingformaddtemplate from './bookingform-addtemplate'
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        components: {
            bookingformaddtemplate
        }
    }

</script>
