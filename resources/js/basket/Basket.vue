<template>
    <div>
        <success v-if="success">Congratulations for your purchase!!</success>
        <div class="row" v-else>
            <div class="col-md-8" v-if="itemsInBasketCount">
                <div class="row form-group">
                    <div class="col-md-6 form-group">
                        <label for="first_name">First Name</label>
                        <input :class="[{'is-invalid' : errorFor('customer.first_name')}]"  type="text" name="first_name" class="form-control" v-model="customer.first_name"/>
                        <v-error :errors ="errorFor('customer.first_name')"></v-error>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="first_name">Last Name</label>
                        <input :class="[{'is-invalid' : errorFor('customer.last_name')}]"  type="text" name="first_name" class="form-control" v-model="customer.last_name"/>
                        <v-error :errors ="errorFor('customer.last_name')"></v-error>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="email">Email</label>
                        <input :class="[{'is-invalid' : errorFor('customer.email')}]" type="text" name="email" class="form-control" v-model="customer.email"/>
                        <v-error :errors ="errorFor('customer.email')"></v-error>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="country">Country</label>
                        <input :class="[{'is-invalid' : errorFor('customer.country')}]"  type="text" name="country" class="form-control" v-model="customer.country"/>
                        <v-error :errors ="errorFor('customer.country')"></v-error>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="state">State</label>
                        <input :class="[{'is-invalid' : errorFor('customer.state')}]"  type="text" name="state" class="form-control" v-model="customer.state"/>
                        <v-error :errors ="errorFor('customer.state')"></v-error>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <button @click.prevent="book()" :disabled="loading" class="btn btn-lg btn-primary btn-block" type="submit">Book Now!</button>
                    </div>
                </div>
            </div>
            <div class="col-md-8" v-else>
                <div class="jumbotron jumbotron-fluid text-center">
                    <h1>Empty</h1>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex justify-content-between">
                    <h6 class="text-uppercase text-secondary font-weight-bolder">Your Cart</h6>
                    <h6 class="badge badge-secondary text-uppercase">
                    <span v-if="itemsInBasketCount"> Items {{itemsInBasketCount}} </span>
                    <span v-else>Empty</span>
                    </h6>
                </div>
                <transition-group>
                    <div v-for="item in basket" :key="item.bookable.id"> 
                    <div class="pt-2 pb-4 border-top d-flex justify-content-between ">
                        <span>
                            <router-link :to="{name:'bookable', params:{'id':item.bookable.id}}">{{item.bookable.title}}</router-link>
                        </span>
                        <span class="font-weight-bold">
                        <i class="fas fa-rupee-sign"></i> {{item.price.total}}
                        </span>
                    </div>
                    <div class="pt-2 pb-4 d-flex justify-content-between">
                        <span>
                            From : {{item.dates.from}}
                        </span>
                        <span>
                            To: {{item.dates.to}}
                        </span>
                    </div>
                    <div class="pt-2 pb-2 text-right">
                        <button class="btn btn-sm btn-outline-secondary" @click="$store.dispatch('removeFromBasket',item.bookable.id)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                </transition-group>   
            </div>
    </div>
</div>
</template>

<script>
import {mapGetters,mapState} from 'vuex'
import validationErrors from '../shared/mixins/validationErrors'
import {is422} from "../shared/utils/response";

export default {
    mixins: [validationErrors],
    data() {
        return {
            loading: false,
            bookingAttempted: false,
            customer: {
                first_name: null,
                last_name: null,
                email: null,
                country: null,
                state: null
            }
        }
    },
    computed: {
        ...mapState({
            basket: state => state.basket.items
        }),
        ...mapGetters(['itemsInBasketCount']),
        success() {
            return !this.loading && this.itemsInBasketCount == 0 && this.bookingAttempted
        }
    },
    methods:{
        async book() {
            this.loading = true;
            this.errors = null;
            this.bookingAttempted = false;
            try {
                await(axios.post('api/checkout',{
                    customer: this.customer,
                    bookings: this.basket.map(basketItem => ({
                        bookable_id : basketItem.bookable.id,
                        from : basketItem.dates.from,
                        to : basketItem.dates.to
                    }))
                }))

                this.$store.dispatch('clearBasket')
                this.bookingAttempted = true;
            } catch (error) {
                if (is422(error)) {
                    this.errors = error.response && error.response.data.errors
                }
            }
            this.loading = false;

        }
    }
}
</script>

<style scoped>
    h6.badge {
        font-size: 100%;
    }
    a {
        color: black;
    }
</style>