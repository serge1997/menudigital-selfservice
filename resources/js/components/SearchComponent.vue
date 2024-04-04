<template>
    <div>
        <div class="col-md-10 m-auto d-flex justify-content-between p-3">
            <InputText v-model="search" class="col-md-12" :placeholder="SearchPlaceholder" @keydown="this.isSpinner = !this.isSpinner"/>
            <button class="btn border rounded-0 text-white" id="search-icon">
                <i v-if="isSpinner" class="pi pi-search" />
                <i v-else class="pi pi-spin pi-spinner" />
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
                            <small class="m-auto" v-if="item.item_rupture"><Tag value="Indisponivel" severity="danger" /></small>
                            <small class="m-auto" v-if="item.is_lowstock"><Tag value="Lowstock" severity="warning" /></small>
                            <h6 class="text-center">{{ item.item_name }}</h6>
                            <span class="text-center text-secondary">{{ item.desc_type }}</span>
                            <small class="col-lg-4 text-center fw-medium m-auto rounded-4 py-2 px-2 price">R$ {{ item.item_price }} </small>
                            <div class="mt-2 d-flex justify-content-center gap-1">
                                <div>
                                    <CartSidebarComponent :rupture="item.item_rupture" @add-to-cart="addToCart(item.id)"/>
                                </div>
                                <Button icon="pi pi-eye" @click="visibleShowItemMenuSearchModal = true; ShowItem(item.id)" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p v-if="notFound" class="text-center alert alert-danger w-25 m-auto">{{ notFound }}</p>
        </div>
        <Dialog v-model:visible="visibleShowItemMenuSearchModal" maximizable modal v-for="item in show" :header="item.item_name" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-100 mt-3">
                <div v-for="item in show" class="d-flex justify-content-between">
                    <div class="w-50">
                        <img class="w-100" src="/img/banner.jpg" alt="">
                        <div class="mt-2 d-flex justify-content-center">
                            <small class="col-lg-4 text-center fw-medium m-auto rounded-4 py-2 px-2 price mt-2">R$ {{ item.item_price }} </small>
                        </div>
                    </div>
                    <div class="px-2"></div>
                    <div class="w-100 d-flex flex-column align-items-center">
                        <ul class="d-flex list-group w-100">
                            <li class="list-group-item bg-dark text-white">Ingredients</li>
                            <li class="list-group-item" v-for="ingredients in fiche">{{ ingredients.prod_name }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </Dialog>
    </div>
</template>

<script>
import axios from 'axios';
import CartSidebarComponent from "@/components/CartSidebarComponent.vue";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dialog from "primevue/dialog";
import Tag from "primevue/tag";

export default {
    name: 'SearchComponent',

    components:{
        CartSidebarComponent,
        Button,
        InputText,
        Dialog,
        Tag
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
            isSpinner: true,
            show: null,
            fiche: null,
            visibleShowItemMenuSearchModal: false
        }
    },

    computed: {

    },

    methods: {
        async getSearchResult(){
            await axios.get('/api/menu-items-search', {params: {search: this.search}}).then((response) =>{
                this.result = response.data

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
