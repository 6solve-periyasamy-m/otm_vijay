//const { default: VueAxios } = require("vue-axios");
import Vuex from 'vuex';
const booking = new Vuex.Store({
    state: {
        lead: {},
        additional: []
    },
    mutuations: {
        setLead(lead) {
            state.lead = lead
        },
        addAdditional(traveller) {
            additional.push(traveller)
        },
        updateAdditional(c, traveller) {
            additional[c] = traveller
        }
    }
})

export default { booking }
