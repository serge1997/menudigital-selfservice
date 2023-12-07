<template>
    <div class="container">
        <div class="w-100">
            <input type="hidden" :value="id" id="order-id">
            <transition>
            <div v-if="newOrderItem.iteminfo || newOrderItem.orderinfo" class="w-75 m-auto border d-flex justify-content-between align-items-center">
                <div class="item-img col-4">
                    <img class="w-50 img-thumbnail" src="./../../../../public/img/banner.jpg" alt="">
                </div>
                <div class="col-4 d-flex flex-column align-items-center">
                    <h6>{{ newOrderItem.iteminfo.item_name }}</h6>
                    <h6>R${{ newOrderItem.iteminfo.item_price }}</h6>
                </div>
                <div class="col-2">
                    <div class="input-group">
                        <button class="btn border card-btn" @click="addItemToOrder.quantity--">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff"
                                stroke-width="6" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </button>
                        <span class="px-1"></span>
                        <input class="form-control border-secondary rounded-0" type="text" v-model="addItemToOrder.quantity">
                        <input type="hidden" :value="newOrderItem.iteminfo.id" id="item-id">
                        <span class="px-1"></span>
                        <button class="btn border card-btn" @click="addItemToOrder.quantity++">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="6"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="4" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="col-2">
                    <button @click="postNewOrderItem" class="btn text-uppercase">ok</button>
                </div>
            </div>
        </transition>
       </div>
        <div class="row">
            <div v-if="load" class="spinner-grow m-auto" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden"></span>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-lg-2 col-md-4 menu-type-card p-2" id="typebt" v-for="mtype in MenuType" :key="mtype.id_type">
                <button class="btn w-75 border-0 d-flex flex-column align-items-center justify-content-center text-capitalize fw-medium" @click.prevent="getItemOfType(mtype.id_type)">
                    <img class="w-50 type-btn" :class="{active: isactive}" :src="'/img/type/'+ mtype.foto_type" alt="">
                    {{ mtype.desc_type }}
                </button>
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
                            <div class="order-btn-box text-white mt-2 d-flex justify-content-end">
                                <button v-if="!item.item_rupture" @click="addToOrder(item.id)" class="btn add-btn p-1 px-2 py-1 border-dark">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
                                        <circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                </button>
                                <p class="alert alert-danger p-2 px-2" v-else>Indisponivel</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else v-for="item in itemOfType" class="col-lg-5 col-md-10 mb-4 m-auto">
                <div class="card rounded-0 shadow">
                    <div class="card-body d-flex p-0">
                        <div class="col-6">
                            <img class="w-100" src="/img/banner.jpg" alt="">
                        </div>
                        <div class="w-100 d-flex flex-column justify-content-between p-1">
                            <h5 class="text-center">{{ item.item_name }}</h5>
                            <span class="text-center text-secondary">{{ item.desc_type }}</span>
                            <h6 class="col-lg-4 text-center m-auto text-white py-2 px-2 shadow rounded-4 price">R$ {{ item.item_price }} </h6>
                            <div class="order-btn-box text-white mt-2 d-flex justify-content-end">
                                <button v-if="!item.item_rupture" @click="addToOrder(item.id)" class="btn add-btn p-1 px-2 py-1 border-dark">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
                                        <circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                </button>
                                <p class="alert alert-danger p-2 px-2" v-else>Indisponivel</p>
                            </div>
                        </div>
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

    props: {
        id: String
    },

    data() {
        return {
            MenuItems: null,
            MenuType: null,
            itemOfType: null,
            show: null,
            addorder: {
                itemID: localStorage.getItem('itemID'),
                orderID: this.id
            },
            isCart: false,
            load: true,
            isRupture: false,
            newOrderItem: {
                orderinfo: null,
                iteminfo: null
            },
            addItemToOrder: {
                quantity: 1,
                orderID: null,
                itemID: null
            },
            isactive: false
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

        getItemOfType(id_type) {
            axios.get('/api/item/type/' + id_type).then((response) => {
                this.itemOfType = response.data.items
            }).catch((errors) => {
                console.log(errors)
            })
        },

        addToOrder(id) {
            axios.post('/api/add-to-order/' + id, this.addorder).then((response) => {
                this.newOrderItem.orderinfo = response.data.order
                this.newOrderItem.iteminfo = response.data.item
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
        },

        postNewOrderItem(){
            this.addItemToOrder.itemID = document.getElementById('item-id').value;
            this.addItemToOrder.orderID = document.getElementById('order-id').value;
            axios.post('/api/new-item', this.addItemToOrder).then((response) => {
                console.log(this.addItemToOrder.itemID)
                this.$toast.success(response.data)
            }).catch((errors) => {
                console.log(errors)
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


    .order-link {
        background-color: #1F2024;
    }

    .order-btn-box {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }

.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
