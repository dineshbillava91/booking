<template>
    <div>
        <h6 class="text-uppercase text-secondary font-weight-bold">Price BreakDown</h6>
        <div v-for="(days,price) in price.breakdown" :key="price" class="pt-2 pb-4 border-bottom border-top d-flex justify-content-between">
            <span>{{days}} X <i class="fas fa-rupee-sign"></i>{{price}}</span>
            <span><i class="fas fa-rupee-sign"></i>{{days*price}}</span>
        </div>
        <div class="font-weight-bolder d-flex justify-content-between">
            <span>Total: </span>
            <span><i class="fas fa-rupee-sign"></i>{{price.total}}</span>
        </div>
    </div>
</template>

<script>
export default {
    props:{
        'price':Object
    },
    data() {
        return {
            
        }
    },
}
</script>