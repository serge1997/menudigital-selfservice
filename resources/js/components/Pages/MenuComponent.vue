<template>
    <div class="container">
        <div class="w-100" id="add-card-container">
            <input type="hidden" :value="id" id="order-id">
            <transition>
                <div id="post-new-item-card" v-if="newOrderItem.iteminfo" class="row m-auto border d-flex justify-content-between align-items-center">
                    <div class="item-img col-lg-3 col-md-10">
                        <img class="w-50 img-thumbnail" src="./../../../../public/img/banner.jpg" alt="">
                    </div>
                    <div class="col-lg-4 col-md-10 d-flex flex-column align-items-center">
                        <h6>{{ newOrderItem.iteminfo.item_name }}</h6>
                        <h6>R${{ newOrderItem.iteminfo.item_price }}</h6>
                    </div>
                    <div class="col-lg-5 col-md-10 d-flex">
                        <Button icon="pi pi-minus" @click="addItemToOrder.quantity--"/>
                        <input style="width: 50px;"lass="form-control border-secondary rounded-0" type="text" v-model="addItemToOrder.quantity">
                        <input type="hidden" :value="newOrderItem.iteminfo.id" id="item-id">
                        <Button icon="pi pi-plus" @click="addItemToOrder.quantity++"/>
                        <button @click="showScanerBox" class="btn text-uppercase">ok</button>
                    </div>
                </div>
            </transition>
       </div>
       <div class="row mt-2 p-3" :class="dNone">
            <div class="col-md-8 m-auto" id="customer-add-qr-reader">
            </div>
       </div>
        <div class="row">
            <div v-if="load" class="spinner-grow m-auto" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden"></span>
            </div>
        </div>
        <div class="row d-flex justify-content-center" id="type-menu">
            <div class="col-lg-2 col-md-4 menu-type-card p-2" id="typebt" v-for="mtype in MenuType" :key="mtype.id_type">
                <button class="btn w-75 border-0 d-flex flex-column align-items-center justify-content-center text-capitalize fw-medium" @click.prevent="getItemOfType(mtype.id_type)">
                    <img class="w-50 type-btn" :class="{active: isactive}" :src="'/img/type/'+ mtype.foto_type" alt="">
                    {{ mtype.desc_type }}
                </button>
            </div>
        </div>
        <div class="row p-4" id="menu-items">
            <div v-if="itemOfType < 1" v-for="item in MenuItems" :key="item.id" class="col-lg-4 col-md-10 mb-4 m-auto">
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
                            <small class="text-center fw-medium m-auto rounded-4 py-2 px-2 price">R$ {{ item.item_price }} </small>
                            <div class="order-btn-box text-white d-flex justify-content-end">
                                <Button v-if="!item.item_rupture" icon="pi pi-cart-plus"  @click="addToOrder(item.id)"/>
                                <Button v-else icon="pi pi-cart-plus" @click="addToOrder(item.id)" disabled />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else v-for="item in itemOfType" class="col-lg-4 col-md-10 mb-4 m-auto">
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
                            <small class="text-center fw-medium m-auto rounded-4 py-2 px-2 price">R$ {{ item.item_price }} </small>
                            <div class="order-btn-box text-white d-flex justify-content-end">
                                <Button v-if="!item.item_rupture" icon="pi pi-cart-plus"  @click="addToOrder(item.id)"/>
                                <Button v-else icon="pi pi-cart-plus" @click="addToOrder(item.id)" disabled />
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
import Dialog from 'primevue/dialog';
import Button from "primevue/button";
import Tag from "primevue/tag";

export default {
    name: 'Menu',

    components: {
        SearchComponent,
        Button,
        Tag,
        Dialog
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
                itemID: null,
                qrcode_order_number: null
            },
            isactive: false,
            checkAlwreadySend: true,
            visibleOrderQrcode: false,
            dNone: 'd-none'
        }
    },

    created() {

        return new Promise((resolve, reject) => {
            setTimeout(() => {
                axios.get('/api/menu-items').then((response) => {
                    this.MenuItems = response.data
                    console.log(response.data.items)
                    this.load = false
                }).catch((errors) => {
                    console.log(errors)
                })
            }, 1000)
        })
    },

    methods: {
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
            axios.get('/api/meal-types/menu-items/filter/' + id_type).then((response) => {
                this.itemOfType = response.data
            }).catch((errors) => {
                console.log(errors)
            })
        },

        addToOrder(id) {
            let cartDiv = document.getElementById('add-card-container');
            cartDiv.style.display = 'block'; //('show-new-item-card')
            axios.get('/api/menu-items/' + id, this.addorder).then((response) => {
                this.newOrderItem.iteminfo = response.data
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
        handleDom(callback) {
            if ( document.readyState === "complete" || document.readyState === "interactive") {
            setTimeout(callback, 1000);
            } else {
            document.addEventListener("DOMContentLoaded", callback);
            }
        },
        mounteQrCodeAddScanner(){
        let self = this;
        if ( ! this.checkAlwreadySend ) {
            this.checkAlwreadySend = !this.checkAlwreadySend;
        }
        this.handleDom(function() {
          function onScanSuccess(decodeText, decodeResult) {
            self.addItemToOrder.qrcode_order_number = decodeText;
            let cartDiv = document.getElementById('add-card-container');
            self.addItemToOrder.itemID = document.getElementById('item-id').value;
            self.addItemToOrder.orderID = document.getElementById('order-id').value;
            cartDiv.style.display = 'none'
            if (self.checkAlwreadySend){
                self.checkAlwreadySend = false
                axios.post('/api/new-item', self.addItemToOrder).then((response) => {
                    self.$toast.success(response.data);
                    location.reload()
                }).catch((errors) => {
                    console.log(errors)
                //this.$toast.error(errors.response.data)
                    if (errors.response.status === 500){
                        //this.visibleRight = false
                        self.$swal.fire({
                            text: !errors.response.data.message ? errors.response.data : errors.response.data.message,
                            icon: "warning"
                        })
                    }
                })
            }
        }
          function onScanFailure(error) {
             //console.warn(`Code scan error = ${error}`);
          }
          let htmlScanner = new Html5QrcodeScanner(
              "customer-add-qr-reader",
              {fps: 10, qrbox: 250}
          );

          htmlScanner.render(onScanSuccess, onScanFailure);
        })
      },
      showScanerBox(){
        this.dNone = null;
        let menuItemsBox = document.getElementById('menu-items');
        menuItemsBox.style.display = "none";
      }
    },

    mounted() {
        this.getMenuType()
        this.mounteQrCodeAddScanner();
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
    transition: opacity .15s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
.hide-new-item-card {
    display: none;
    width: 0px;
}
.show-new-item-card {
    display: block;
}
</style>
