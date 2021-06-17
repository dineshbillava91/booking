<template>
   <div class="text-center success">
        <i class="fas fa-thumbs-up">
            <h1 class="mt-4">Success!</h1>
            <h2 class="mt-4">
                <slot></slot>
            </h2>
        </i>
    </div>
</template>

<script>
export default {
    
}
</script>
<style scoped>
    .success {
        color: #999;
        font-size: 10rem;
    }
</style>