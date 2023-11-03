<template>
    <div class="fade-enter-active">
        <router-view></router-view>
    </div>
</template>

<script>
import VueAxios from 'vue-axios';

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

    #search-icon {
        background-color: #333;
    }

    .dropdown-btn:hover {
        background-color: #eff3f7;
        box-shadow: 1px 8px #3333338a;
        transition: .3s ease;
    }

</style>