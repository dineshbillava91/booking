<template>
<div>
    <div v-if="error == 404">
        <fatal-error></fatal-error>
    </div>
    <div v-else class="row">
        <div class="col-md-8 pb-4">
            <div class="card">
                <div class="card-body">
                    <div v-if="!loading"> 
                        <h5 class="title">{{bookable.title}}</h5>
                        <hr>
                        <article>{{bookable.description}}</article>
                    </div>
                    <div v-else>
                        Loading.........!!!!!
                    </div> 
                </div>
            </div>
        <reviews-list :bookable-id = this.$route.params.id></reviews-list>
        </div>
        <div class="col-md-4 pb-4">
        <availability :bookable-id = $route.params.id @availability="checkPrice($event)" class="mb-4"></availability>
            <transition name="fade">
                <price-breakdown v-if="price" :price="price" class="mb-4"></price-breakdown>
            </transition>
            <transition name="fade">
                <button class="btn btn-outline-secondary btn-block" v-if="price" @click="addToBasket()" :disabled="inBasketAlready">Add to Basket</button>
            </transition>
            <transition name="fade">
                <button class="btn btn-outline-secondary btn-block" v-if="price && inBasketAlready" @click="removeFromBasket()" :disabled="!inBasketAlready">Remove From Basket</button>
            </transition>
            <div v-if="inBasketAlready" class="mt-4 text-muted warning">
                You've already added bokable in basket, Please remove if you need to add new item
            </div>
        </div>
    </div>
    

</div>
</template>

<script>
import Availability from './Availability'
import ReviewsList from './ReviewsList'
import PriceBreakdown from './PriceBreakdown'
import {mapGetters, mapState} from 'vuex'

export default {
   
    data() {
        return {
            bookable : null,
            loading : false,
            price: null,
            error:false
        }
    },
    components: {
        Availability,
        ReviewsList,
        PriceBreakdown
    },
    created() {
        this.loading = true;
        axios.get(`/api/bookables/${this.$route.params.id}`).then(
            response => {
                this.bookable = response.data.data
                this.loading = false
            }
        ).catch(error => this.error = error.response.status)
    },
    computed: {
        ...mapState({
            lastSearch:'lastSearch',
        }),
        inBasketAlready() {
            if (this.bookable == null) return false;
            return this.$store.getters.inBasketAlready(this.bookable.id)
        }
    },
    methods:{
        async checkPrice(hasAvailability) {
            if(!hasAvailability) {
                this.price = null
                return;
            }

            try {
                this.price = (await axios.get(`/api/bookables/${this.bookable.id}/price?from=${this.lastSearch.from}&to=${this.lastSearch.to}`)).data.data
            } catch (error) {
                this.price = null
            }
        },
        addToBasket() {
            this.$store.dispatch('addToBasket',{
                bookable: this.bookable,
                price: this.price,
                dates:this.lastSearch
            })
        },
        removeFromBasket() {
            this.$store.dispatch('removeFromBasket',this.bookable.id)
        }
    }
}
</script>
<style scoped>
    .warning {
        font-size: 0.7rem;
    }
</style>