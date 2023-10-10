<template>
    <div class="fade-enter-active">
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: 'App',

    mounted(){
        
    }
}

</script>

<style>
    .fade-enter-active {
        transition: opacity 0.5s ease;
    }
    .btn-single-style {
        background-color: #e2e8f0;
    }

</style>