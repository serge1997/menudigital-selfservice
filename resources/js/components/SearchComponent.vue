<template>
    <div>
        <div class="col-md-10 m-auto d-flex justify-content-between p-3">
            <span class="w-100 p-input-icon-left">
                <i v-if="isSpinner" class="pi pi-search" />
                <i v-else class="pi pi-spin pi-spinner" />
                <InputText v-model="search" class="w-100" :placeholder="SearchPlaceholder" @keydown="this.isSpinner = !this.isSpinner"/>
            </span>
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
                            <div class="mt-2 d-flex justify-content-center gap-1">
                                <div>
                                    <CartSidebarComponent @add-to-cart="addToCart(item.id)"/>
                                </div>
                                <Button @click.prevent="ShowItem(item.id)" text data-bs-toggle="modal" class="btn-eye" data-bs-target="#staticBackdrop" icon="pi pi-eye"/>
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
import CartSidebarComponent from "@/components/CartSidebarComponent.vue";
import Button from "primevue/button";
import InputText from "primevue/inputtext";

export default {
    name: 'SearchComponent',

    components:{
        CartSidebarComponent,
        Button,
        InputText
    },

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
            notFound: null,
            table: {
                tableNumber: localStorage.getItem('table'),
            },
            isSpinner: true
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
        },
        addToCart(id) {
            axios.post('/api/add/cart/' + id, this.table).then((response) => {
                console.log(response.data)
            }).catch((errors) => {
                console.log(errors)
            })
        },

        ShowItem(id){
            axios.get('/api/show/'+id).then((response) => {
                console.log(response.data);
                this.show = response.data.item
                this.fiche = response.data.fiche
            }).catch((errors) => {
                console.log(errors);
            })
        },

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
