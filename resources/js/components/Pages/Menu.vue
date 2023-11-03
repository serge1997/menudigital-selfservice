<template>
    <div class="container">
       <div class="d-flex justify-content-between">
            <div class="w-100 fantasy p-3 d-flex flex-column align-items-center">
                <h6 class="fs-2">Casino bar</h6>
                <h6 class="fs-6">Menu digital</h6>
            </div>
            <div class="p-2">
                <router-link class="d-flex nav-link" :to="{ name: 'Cart' }">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" 
                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z">
                        </path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </router-link>
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
            <div class="col-lg-2 col-md-4 border menu-type-card p-2 mt-2" id="typebtn" v-for="mtype in MenuType" :key="mtype.id_type">
                <button class="btn w-100 d-flex flex-column align-items-center justify-content-center text-capitalize fw-medium" @click.prevent="getItemOfType(mtype.id_type)">  
                    <img class="w-25" :src="'/img/type/'+ mtype.foto_type" alt="">
                    {{ mtype.desc_type }}
                </button>
            </div>
        </div>
        <div class="row p-4">
            <div v-if="itemOfType < 1" v-for="item in MenuItems" :key="item.id" class="col-lg-5 col-md-10 mb-4 m-auto" disabled>
                <div class="card rounded-0 p-0">
                    <div class="card-body d-flex p-0">
                        <div class="col-6">
                            <img class="w-100 h-100 rounded-0 card-img-top" src="img/banner.jpg" alt="">
                        </div>
                        <div class="w-100 d-flex flex-column justify-content-between p-1">
                            <h6 class="text-center">{{ item.item_name }}</h6>
                            <span class="text-center text-secondary">{{ item.desc_type }}</span>
                            <h6 class="col-lg-4 text-center m-auto text-white py-2 px-2 shadow rounded-4 price">R$ {{ item.item_price }} </h6>
                            <div class="order-btn-box text-white mt-2">
                                <router-link v-if="!item.item_rupture" @click.prevent="addToCart(item.id)" :to="{ name: 'ItemCart', params: {id:item.id}}" class="nav-link px-3 py-1 text-black border rounded-3">add</router-link>
                                <p class="alert alert-danger p-2 px-2" v-else>Indisponivel</p>
                                <button class="border-0" data-bs-toggle="modal" data-bs-target="#staticBackdrop" @click.prevent="ShowItem(item.id)">  
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" 
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else v-for="item in itemOfType" class="col-lg-5 col-md-10 mb-4 m-auto">
                <div class="card rounded-0 shadow">
                    <div class="card-body d-flex p-0">
                        <div class="col-6">
                            <img class="w-100" src="img/banner.jpg" alt="">
                        </div>
                        <div class="w-100 d-flex flex-column justify-content-between p-1">
                            <h5 class="text-center">{{ item.item_name }}</h5>
                            <span class="text-center text-secondary">{{ item.desc_type }}</span>
                            <h6 class="col-lg-4 text-center m-auto text-white py-2 px-2 shadow rounded-4 price">R$ {{ item.item_price }} </h6>
                            <div class="order-btn-box text-white mt-2">
                                <router-link @click.prevent="addToCart(item.id)" :to="{ name: 'ItemCart', params: {id:item.id}}" class="nav-link px-3 py-1 text-black border rounded-3">add</router-link>
                                <button class="btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop" @click.prevent="ShowItem(item.id)">  
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" 
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
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
                                <img class="w-100" src="img/banner.jpg" alt="">
                            </div>
                            <div class="w-50 d-flex flex-column justify-content-between align-items-center">
                                <div>
                                    <p class="text-center text-uppercase fw-medium">{{ item.item_name }}</p>
                                    <p class="text-center p-4"><small>{{ item.item_desc }}</small></p>
                                </div>
                                <div>
                                    <h6 class="text-center m-auto text-white py-2 px-4 shadow rounded-4 price">R$ {{ item.item_price }} </h6>
                                </div>
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

export default {
    name: 'Menu',

    components: {
        SearchComponent
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
            isRupture: false
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
                    axios.get('/api/get/menutype').then((response) => {
                        this.MenuType = response.data
                    }).catch((errors) => {
                        console.log(errors)
                    })
                }, 1000)
            })
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
                this.show = response.data
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
    #typebtn {
        background-color: #eff3f7;
        transition: all .3s ease;
        position: relative;
        z-index: 10;
    }

    #typebtn::before {
        content: '';
        position: absolute;
        width: 0%;
        height: 110%;
        transform: translateX(-15px) translateY(-7px);
        transition: all .4s ease;
        z-index: -1;
        background-color: #fff;
    }

    #typebtn:hover::before {
        width: 180%;
    }

    .price {
        background-color: #1F2024 ;
        font-size: 0.8em;
    }

    .order-link {
        background-color: #1F2024;
    }

    .order-btn-box {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }
</style>