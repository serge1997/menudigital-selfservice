<template>
    <div class="row">
        <div class="col-md-12 d-flex justify-content-center m-auto p-3">
            <InputText class="w-75" v-model="search" :placeholder="SearchPlaceholder" @keydown="this.isSpinner = !this.isSpinner"/>
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
                            <div class="order-btn-box text-white d-flex justify-content-end mt-1">
                                <Button icon="pi pi-eye" text style="background-color: #e2e3e5;" @click="$emit('ShowItem', item.id) " />
                                <Button v-if="!item.item_rupture" icon="pi pi-cart-plus"  @click="$emit('AddToCart', item.id)"/>
                                <Button v-else icon="pi pi-cart-plus" @click="$emit('AddToCart', item.id)" disabled />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p v-if="notFound" class="text-center alert alert-danger w-50 m-auto">{{ notFound }}</p>
        </div>
    </div>
</template>

<script>
import Sidebar from "primevue/sidebar";
import Accordion from "primevue/accordion";
import AccordionTab from "primevue/accordiontab";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
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
        Tag,
        Sidebar,
        Accordion,
        AccordionTab,
        DataTable,
        Column

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
            fiche: null,
            visibleShowItemMenuSearchModal: false,
            cartItems: null,
            cart: {
                comments: null,
                options: [],
                tableNumber: localStorage.getItem('table'),
                ped_tableNumber: localStorage.getItem('table'),
                user_id: null,
                ped_customerName: null,
                ped_customer_quantity: null,
            },
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
        // addToCart(id) {
        //     axios.post('/api/add-to-cart/' + id, this.table).then((response) => {
        //         console.log(response.data)
        //         this.cartItems = response.data
        //         this.visibleRight = true;
        //     }).catch((errors) => {
        //         console.log(errors)
        //     })
        // },

        // ShowItem(id){
        //     axios.get('/api/menu-items/fiche/' +id).then((response) => {
        //         this.show = response.data.item
        //         this.fiche = response.data.fiche
        //         this.visibleShowItemMenuSearchModal = true;
        //     }).catch((errors) => {
        //         console.log(errors);
        //     })
        // },

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
