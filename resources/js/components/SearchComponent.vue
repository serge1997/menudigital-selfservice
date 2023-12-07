<template>
    <div>
        <div class="col-md-10 m-auto d-flex justify-content-between p-3">
            <input type="search" :placeholder="SearchPlaceholder" v-model="search" class="form-control rounded-0 search-input">
            <button class="btn border rounded-0" id="search-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                    <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </div>
        <div class="row p-4">
            <div v-if="result" v-for="item in result" :key="item.id" class="col-lg-5 col-md-10 mb-4 m-auto">
                <div class="card rounded-0 p-0">
                    <div class="card-body d-flex p-0">
                        <div class="col-6">
                            <img class="w-100 h-100 rounded-0 card-img-top" src="/img/banner.jpg" alt="">
                        </div>
                        <div class="w-100 d-flex flex-column justify-content-between p-1">
                            <h6 class="text-center">{{ item.item_name }}</h6>
                            <span class="text-center text-secondary">{{ item.desc_type }}</span>
                            <h6 class="col-lg-4 text-center m-auto text-white py-2 px-2 shadow rounded-4 price">R$ {{ item.item_price }} </h6>
                            <div class="order-btn-box text-white mt-2">
                                <router-link v-if="!item.item_rupture" @click="$emit('AddToCart', item.id)" :to="{ name: 'ItemCart', params: {id:item.id}}" class="btn add-btn p-1 px-2 py-1 border-dark">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
                                        <circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                </router-link>
                                <p class="alert alert-danger p-2 px-2" v-else>Indisponivel</p>
                                <button class="btn add-btn p-1 px-2 py-1 show-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop" @click.prevent="$emit('ShowItem', item.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63958" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p v-if="notFound" class="text-center alert alert-danger w-25 m-auto">{{ notFound }}</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'SearchComponent',

    props: {
        SearchPlaceholder: String
    },

    watch: {
        search(before, after){
            this.getSearchResult();
        }
    },

    data(){
        return {
            result: null,
            search: null,
            notFound: null
        }
    },

    computed: {

    },

    methods: {
        async getSearchResult(){
            await axios.get('/api/search/', {params: {search: this.search}}).then((response) =>{
                this.result = response.data.items

                if (this.result.length < 1){
                    this.notFound = "Não há item corespondante";
                }else{
                    this.notFound = ""
                }

                console.log(response.data);
            })
    }
    }
}
</script>

<style scoped>


.search-input{
    border: 2px solid #e2e8f0;
}


.order-btn-box {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
    }
</style>
