<template>
    <div class="d-flex">
        <i v-for="star in fullStars" :key= "'full' + star" class="fas fa-star" @click="$emit('input',star)"></i>
        <i v-if="halfStar" class="fas fa-star-half-alt"></i>
        <i v-for="star in emptyStars" :key="'empty' + star" class="far fa-star" @click="$emit('input', fullStars+star)"></i>
    </div>
</template>

<script>
export default {
    props: {
        value: Number //use value only, emit input
    },  
    computed: {
        halfStar() {
            const fraction = this.value - Math.floor(this.value);
            return fraction > 0 && fraction;
        },
        fullStars() {
            return Math.round(this.value);
        },
        emptyStars() {
            return 5-Math.ceil(this.value);
        }
    }
}
</script>