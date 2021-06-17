<template>
    <div>
        <h6 class="text-uppercase text-secondary font-weight-bold">
            Check Availability
            <transition>
                <span v-if="hasAvailability" class="text-success"> Available</span>
                <span v-if="status != null && !hasAvailability && !hasErrors" class="text-danger"> Not Available</span>
            </transition>
        </h6>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="from">From</label>
                <input class = "form-control form-control-sm" type="date" name="from" placeholder="Start Date" 
                v-model="from" @keyup.enter="check()" 
                :class="[{'is-invalid' : errorFor('from')}]">
                 <v-error :errors ="errorFor('from')"></v-error>
            </div>
            <div class="form-group col-md-6">
                 <label for="to">To</label>
                <input class = "form-control form-control-sm" type="date" name="to" placeholder="End Date" 
                v-model="to" @keyup.enter="check()"
                :class="[{'is-invalid' : errorFor('to')}]">
                  <v-error :errors ="errorFor('to')"></v-error>
            </div>
        </div>
        <!-- <button class="btn btn-secondary btn-block" v-on:click="check()">Check</button> -->
        <button class="btn btn-secondary btn-block" @click="check()" :disabled="loading">
            <span v-if="!loading">Check!</span>
            <span v-if="loading"><i class="fas fa-spinner"></i>Checking...</span>
        </button>
    </div>
</template>
<script>
import {is422} from "../shared/utils/response";
import validationErrors from '../shared/mixins/validationErrors';

export default {
    mixins: [validationErrors],
    props: {
        bookableId: [String, Number]
    },
    data() {
        return {
            from: this.$store.state.lastSearch.from,
            to: this.$store.state.lastSearch.to,
            loading : false,
            status: null
        }
    },
    methods: {
        async check() {
            this.loading = true;
            this.errors = null;

            //set from and to using mutation setLastSearch vuex store, dispatch actions
            this.$store.dispatch('setLastSearch', {
                from: this.from,
                to: this.to
            });

            try {
                this.status = (await axios.get(`/api/bookables/${this.bookableId}/availability?from=${this.from}&to=${this.to}`)).status
                this.loading = false
                this.$emit('availability',this.hasAvailability)
            } catch (error) {
                if (is422(error)) {
                    this.errors = error.response.data.errors
                }
                this.status = error.response.status
                this.$emit('availability',this.hasAvailability)
            }

            this.loading = false

            //or
            // axios.get(`/api/bookable/${this.bookableId}/availability?from=${this.from}&to=${this.to}`)
            // .then(response => {
            //     this.status  = response.status
            //     this.loading  = false
            // }).catch(error => {
            //     if (is422(error)) {
            //         this.errors = error.response.data.errors
            //     }

            //     this.status = error.response.status

            // }).then(
            //     this.loading = false
            // );
        }
    },
    computed :{
        hasErrors() {
            return 422 === this.status && this.errors != null
        },

        hasAvailability() {
            return 200 === this.status
        },

        // noAvailability() {
        //     return 404 === this.status
        // }
    }
}
</script>
<style scoped>
label {
    font-size: 0.7rem;
    text-transform: uppercase;
    color: gray;
    font-weight: bolder;
}
</style>
