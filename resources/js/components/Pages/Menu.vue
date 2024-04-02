<template>
    <div class="container">
       <div class="d-flex justify-content-between">
            <div class="w-100 fantasy p-3 d-flex flex-column align-items-center">
                <h6 class="fs-2">Casino bar</h6>
                <h6 class="fs-6">Menu digital</h6>
            </div>
            <div class="p-2 d-flex">
                <div>
                    <router-link class="d-flex nav-link" :to="{ name: 'Cart' }">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z">
                        </path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                    </router-link>
                </div>
                <p class="px-2"></p>
                <div>
                    <router-link class="d-flex nav-link" :to="{name: 'Garcom'}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </router-link>
                </div>
            </div>
       </div>
       <div class="row">
        <SearchComponent :show="show" -search-placeholder="Search item here..." @add-to-cart="addToCart" @show-item="ShowItem"></SearchComponent>
       </div>
        <div class="row">
            <div v-if="load" class="spinner-grow m-auto" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden"></span>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="row d-flex justify-content-center p-3">
                <div class="col-lg-2 col-md-4 d-flex justify-content-center menu-type-card p-2" id="typebt" v-for="mtype in MenuType" :key="mtype.id_type">
                    <button class="btn w-75 border-0 d-flex flex-column align-items-center justify-content-center text-capitalize fw-medium" @click.prevent="getItemOfType(mtype.id_type)">
                        <img class="w-50 col-md-6 type-btn" :src="'/img/type/'+ mtype.foto_type" alt="">
                        {{ mtype.desc_type }}
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div v-if="loadBarType" class="col-md-8 d-flex flex-column mb-3 m-auto">
                <small class="text-center">Aguarde...</small>
                <ProgressBar mode="indeterminate" style="height: 6px;"/>
            </div>
        </div>
        <div class="row p-4">
            <div v-if="!itemOfType" v-for="item in MenuItems" :key="item.id" class="col-lg-3 col-md-10 mb-4 m-auto" disabled>
                <div class="card rounded-0 border-0 p-0 col-md-8">
                    <div class="card-body border shadow-sm d-flex flex-column p-0">
                        <div class="col-md-12">
                            <img class="w-100 rounded-0 card-img-top" src="/img/banner.jpg" alt="">
                        </div>
                        <div class="col-md-12 d-flex flex-column justify-content-between">
                            <small class="m-auto p-1" v-if="item.item_rupture"><Tag value="Indisponivel" severity="danger" /></small>
                            <small class="m-auto p-1" v-if="item.is_lowstock"><Tag value="Lowstock" severity="warning" /></small>
                            <small class="m-auto p-3" v-if="!item.is_lowstock && !item.item_rupture"></small>
                            <h6 class="text-center">{{ item.item_name }}</h6>
                            <small class="text-center fw-medium m-auto rounded-4 py-1 px-2 price">R$ {{ item.item_price }} </small>
                            <div class="order-btn-box text-white d-flex justify-content-end">
                                <Button v-if="!item.item_rupture" icon="pi pi-cart-plus"  @click="addToOrder(item.id)"/>
                                <Button v-else icon="pi pi-cart-plus" @click="addToOrder(item.id)" disabled />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else v-for="item in itemOfType" class="col-lg-3 col-md-10 mb-4 m-auto">
                <div class="card rounded-0 border-0 p-0 col-lg-8 col-md-12 m-auto">
                    <div class="card-body border shadow-sm d-flex flex-column p-0">
                        <div class="col-md-12">
                            <img class="w-100 rounded-0 card-img-top" src="/img/banner.jpg" alt="">
                        </div>
                        <div class="col-md-12 d-flex flex-column justify-content-between">
                            <small class="m-auto p-1" v-if="item.item_rupture"><Tag value="Indisponivel" severity="danger" /></small>
                            <small class="m-auto p-1" v-if="item.is_lowstock"><Tag value="Lowstock" severity="warning" /></small>
                            <small class="m-auto p-3" v-if="!item.is_lowstock && !item.item_rupture"></small>
                            <h6 class="text-center">{{ item.item_name }}</h6>
                            <small class="text-center fw-medium m-auto rounded-4 py-1 px-2 price">R$ {{ item.item_price }} </small>
                            <div class="order-btn-box text-white d-flex justify-content-end">
                                <Button v-if="!item.item_rupture" icon="pi pi-cart-plus"  @click="addToOrder(item.id)"/>
                                <Button v-else icon="pi pi-cart-plus" @click="addToOrder(item.id)" disabled />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Dialog v-model:visible="visibleShowItemMenuModal" maximizable modal :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-100 mt-3">
                <div v-if="loadBar" class="col-md-8 d-flex flex-column mb-3 m-auto">
                    <small class="text-center">Aguarde...</small>
                    <ProgressBar mode="indeterminate" style="height: 6px;"/>
                </div>
                <div v-if="show" class="d-flex justify-content-between">
                    <div class="w-50">
                        <img class="w-100" src="/img/banner.jpg" alt="">
                        <div class="mt-2 d-flex justify-content-center">
                            <small class="col-lg-4 text-center fw-medium m-auto rounded-4 py-2 px-2 price mt-2">R$ {{ show.item_price }} </small>
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
        <Sidebar v-model:visible="visibleRight" header="Cart" position="right" class="col-lg-8 col-md-12">
            <Accordion class="p-accordion" :activeIndex="0">
                <AccordionTab v-for="item in cartItems" class="p-accordion-header" :header="item.item_name" style="color: #fff">
                    <div class="col-md-12 d-flex justify-content-evenly">
                        <div class="col-md-2 item-img d-flex justify-content-start">
                            <img alt="item menu image" class="img-thumbnail w-100" src="img/banner.jpg">
                        </div>
                        <div class="col-md-6 d-flex justify-content-center align-items-center">
                            <div class="col-md-2">
                                <Button class="w-100" @click="AddQuantity(item.cart_id)" icon="pi pi-plus" />
                            </div>
                            <div class="col-md-4 d-flex flex-column">
                                <InputText class="text-center w-100" type="text" :value="item.quantity"/>
                            </div>
                            <div class="col-md-2">
                                <Button class="w-100" @click="ReduceQuantity(item.cart_id)" icon="pi pi-minus" />
                            </div>
                            <div class="w-100 d-flex justify-content-center bg-white">
                                <span class="fw-medium">{{ item.total}} <small>R$</small></span>
                            </div>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <Button @click="DeleteFromCart(item.cart_id)" severity="danger" icon="pi pi-times"/>
                        </div>
                    </div>
                    <div>
                        <InputText v-model="cart.comments" class="w-75 p-3 mt-3" placeholder="Customer comment" />
                    </div>
                    <div class="w-100 mt-3 p-3">
                        <div class="d-flex flex-wrap gap-3">
                            <div v-for="option in options" class="flex align-items-center gap-2">
                                <!-- <RadioButton v-model="cart.options" name="pizza" :value="option.option_name" />
                                <label for="ingredient1" class="ml-2">{{ option.option_name }}</label> -->
                            </div>
                        </div>
                    </div>
                </AccordionTab>
            </Accordion>
            <div class="w-100 p-3">
                <h6 class="text-uppercase text-center p-2">Order information</h6>
                <div class="w-100">
                    <label for="customer-name">Customer name</label>
                    <InputText class="w-100" id="customer-name" v-model="cart.ped_customerName" type="text" placeholder="customer name" />
                    <small class="text-danger" v-if="errMsg" v-text="errMsg[0]"></small>
                    <label for="ped_customer_total" class="mt-3">Customer Total</label>
                    <InputText v-model="cart.ped_customer_quantity"  class="w-100" placeholder="Total customer" />
                </div>
                <div class="w-100 p-3">
                    <DataTable :value="cartItems">
                        <Column field="item_name" header="Item name"></Column>
                        <Column field="quantity" header="Quantity"></Column>
                        <Column field="unit_price" header="Price"></Column>
                        <Column field="total" header="Subtotal"></Column>
                    </DataTable>
                </div>
            </div>
            <div class="w-100 d-flex mt-3 justify-content-end">
                <Button @click="confirmOrder" label="Confirm order"/>
            </div>
        </Sidebar>
    </div>
</template>

<script>
import axios from 'axios';
import Sidebar from "primevue/sidebar";
import Accordion from "primevue/accordion";
import AccordionTab from "primevue/accordiontab";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import SearchComponent from '../SearchComponent.vue';
import Button from "primevue/button";
import DataView from "primevue/dataview";
import Tag from "primevue/tag";
import Dialog from "primevue/dialog";
import ProgressBar from 'primevue/progressbar';
import InputText from "primevue/inputtext";
import { randTime } from './../../rand';

export default {
    name: 'Menu',

    components: {
        SearchComponent,
        Button,
        DataView,
        Tag,
        Dialog,
        ProgressBar,
        Sidebar,
        Accordion,
        AccordionTab,
        DataTable,
        Column,
        InputText
    },

    data() {
        return {
            visibleRight: false,
            cartItems: null,
            options: null,
            MenuItems: null,
            MenuType: null,
            itemOfType: null,
            show: null,
            table: {
                tableNumber: localStorage.getItem('table')
            },
            isCart: false,
            load: true,
            loadBar: false,
            loadBarType: false,
            isRupture: false,
            fiche: null,
            visibleRight: false,
            visibleShowItemMenuModal: false,
            quantity: null,
            total: null,
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

    created() {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                axios.get('/api/menu-items').then((response) => {
                    this.MenuItems = response.data
                    this.load = false
                }).catch((errors) => {
                    console.log(errors)
                })
            }, randTime())
        })
    },

    methods: {

        async getCartItem(){
           return new Promise( async resolve => {
                const cartResponse = await axios.get('/api/cart-itens/'+ this.table.tableNumber);
                this.cartItems = await cartResponse.data.items
                this.options = await cartResponse.data.options
                resolve(true)

           })
        },

        getMenuType() {
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    axios.get('/api/meal-types/menu-items').then((response) => {
                        this.MenuType = response.data
                    }).catch((errors) => {
                        console.log(errors)
                    })
                }, 1000)
            })
        },

        getItemOfType(id_type) {
            this.loadBarType = true
            return new Promise(resolve => {
                setTimeout(() => {
                    axios.get('/api/meal-types/menu-items/filter/' + id_type)
                    .then(async (response) => {
                        this.itemOfType = await response.data;
                        this.loadBarType = false
                        resolve(true);
                    }).catch((errors) => {
                        console.log(errors)
                    })
                }, 1000)
            })
        },

        addToCart(id) {
            axios.post('/api/add-to-cart/' + id, this.table).then((response) => {
               this.visibleRight = true;
               this.cartItems = response.data
               console.log(response.data)
            }).catch((errors) => {
                console.log(errors)
            })
        },

        checkCart(checkTable = this.table.tableNumber) {
            axios.get('/api/checkcart/' + checkTable).then((response) => {
                this.isCart = response.data
            }).catch((errors) => {
                console.log(errors)
            })
        },

        async ShowItem(id){
            this.loadBar = true;
            this.show = null;
            this.fiche = null;
            return new Promise(async resolve => {
                try{
                    setTimeout( async () => {
                        const response = await axios.get('/api/menu-items/fiche/'+id)
                        this.show = await response.data.item
                        this.fiche = await response.data.fiche
                        this.loadBar = false
                        resolve(true)
                    }, randTime())
                }catch(errors){
                    console.log(errors.response)
                }
            })
        },

        async AddQuantity(id) {
           try{
                const quantityResponse = await axios.put(`/api/cart-add/quantity/${id}`);
                this.quantity = await quantityResponse.data.quantity
                this.total = await quantityResponse.data.total
                return this.getCartItem();
           }catch(errors){
                console.log(errors)
           }

        },

        async ReduceQuantity(id) {
           try{
                const reduceResponse =  await axios.put('/api/cart-reduce/quantity/' + id)
                this.quantity = await reduceResponse.data.quantity
                this.total = await reduceResponse.data.total
                return this.getCartItem()
           }catch(errors){
                console.log(errors)
           }

        },

        confirmOrder() {
            axios.post('/api/order', this.cart).then((response) => {
                console.log(response.data)
                this.$router.push('/dashboard/garcom')
                this.$toast.success(response.data)
            }).catch((errors) => {
                console.log(errors)
                //this.$toast.error(errors.response.data)
                if (errors.response.status === 500){
                    this.visibleRight = false
                    this.$swal.fire({
                        text: !errors.response.data.message ? errors.response.data : errors.response.data.message  ,
                        icon: "warning"
                    })
                }
                this.errMsg = errors.response.data.errors.ped_customerName
            })
        },

        async DeleteFromCart(id) {
            await axios.delete(`/api/cart-item/${id}`)
            return this.getCartItem();
        },

        stockCureentlyCheck(){
            axios.put("/api/current/stock-rest").then((response) => {
                console.log(response);
            })
        }

    },

    mounted() {
        this.getMenuType()
        this.stockCureentlyCheck()
    }
}

</script>

<style scoped>

.fantasy {
    font-family: 'Borel';
}

.order-btn-box {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}


.menu-type-card {
    border-bottom: 1px #e63958 solid;
}

.type-btn {
    width: 25%;
    padding: 8px;
    border-radius: 50%;
    border: 1px solid #e63958;
    transition: all .4s ease-in;
}

.menu-type-card:hover .type-btn{
    background-color: #e63958;
}

.menu-type-card:active .type-btn{
    background-color: #e63958;
}


</style>
