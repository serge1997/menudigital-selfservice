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
        <SearchComponent -search-placeholder="Search item here..." @add-to-cart="addToCart" @show-item="ShowItem"></SearchComponent>
       </div>
        <div class="row">
            <div v-if="load" class="spinner-grow m-auto" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden"></span>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="row d-flex justify-content-center p-3">
                <div class="col-lg-2 col-md-4 menu-type-card p-2" id="typebt" v-for="mtype in MenuType" :key="mtype.id_type">
                    <button class="btn w-75 border-0 d-flex flex-column align-items-center justify-content-center text-capitalize fw-medium" @click.prevent="getItemOfType(mtype.id_type)">
                        <img class="w-50 type-btn" :src="'/img/type/'+ mtype.foto_type" alt="">
                        {{ mtype.desc_type }}
                    </button>
                </div>
            </div>
        </div>
        <div class="row p-4">
            <div v-if="itemOfType < 1" v-for="item in MenuItems" :key="item.id" class="col-lg-5 col-md-10 mb-4 m-auto" disabled>
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
            <div v-else v-for="item in itemOfType" class="col-lg-5 col-md-10 mb-4 m-auto">
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
        </div>
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h1 v-for="item in show" class="modal-title fs-5" id="staticBackdropLabel">{{ item.item_name }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div v-for="item in show" class="d-flex justify-content-between">
                            <div class="w-50">
                                <img class="w-100" src="/img/banner.jpg" alt="">
                                <h6 class="col-lg-4 text-center m-auto text-white py-2 px-2 mt-2 shadow rounded-4 price">R$ {{ item.item_price }} </h6>
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
                    <div class="modal-footer">
                        <!--<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Understood</button>-->
                    </div>
                    </div>
                </div>
            </div>
    </div>
</template>

<script>
import axios from 'axios';
import SearchComponent from '../SearchComponent.vue';
import CartSidebarComponent from "@/components/CartSidebarComponent.vue";
import Button from "primevue/button";
import DataView from "primevue/dataview";

export default {
    name: 'Menu',

    components: {
        SearchComponent,
        CartSidebarComponent,
        Button,
        DataView
    },

    data() {
        return {
            MenuItems: null,
            MenuType: null,
            itemOfType: null,
            show: null,
            table: {
                tableNumber: localStorage.getItem('table')
            },
            isCart: false,
            load: true,
            isRupture: false,
            fiche: null,
            visibleRight: false
        }
    },

    created() {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                axios.get('/api/menu/items').then((response) => {
                    this.MenuItems = response.data.items
                    console.log(response.data.items)
                    this.load = false
                }).catch((errors) => {
                    console.log(errors)
                })
            }, 1000)
        })
        //this.getMenuItems()
       // this.checkCart()
    },

    methods: {

        getMenuType() {
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    axios.get('/api/menu-type').then((response) => {
                        this.MenuType = response.data
                    }).catch((errors) => {
                        console.log(errors)
                    })
                }, 1000)
            })
        },
        Test(id){
            alert(id)
        },
        getItemOfType(id_type) {
            axios.get('/api/item/type/' + id_type).then((response) => {
                this.itemOfType = response.data.items
            }).catch((errors) => {
                console.log(errors)
            })
        },

        addToCart(id) {
            axios.post('/api/add/cart/' + id, this.table).then((response) => {
                console.log(response.data)
            }).catch((errors) => {
                console.log(errors)
            })
        },

        checkCart(checkTable = this.table.tableNumber) {
            axios.get('/api/checkcart/' + checkTable).then((response) => {
                this.isCart = response.data
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
        }

    },

    mounted() {
        this.getMenuType()
        //this.getMenuItems()
        //this.checkCart()
        //localStorage.removeItem('userRole')
        //console.log(localStorage.getItem('table'))
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
