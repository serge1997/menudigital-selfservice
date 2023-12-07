<template>
    <div class="container">
        <div class="w-100 fantasy p-3 d-flex flex-column align-items-center">
            <h6 class="fs-2">Casino bar</h6>
            <h6 class="fs-6">Menu digital</h6>
        </div>
        <div class="row d-flex justify-content-center p-3">
            <div class="col-lg-2 col-md-4 menu-type-card p-2 mt-2" id="typebtn" v-for="mtype in MenuType" :key="mtype.id_type">
                <button class="btn w-75 border-0 d-flex flex-column align-items-center justify-content-center text-capitalize fw-medium" @click.prevent="getItemOfType(mtype.id_type)">
                    <img class="w-50 type-btn" :src="'/img/type/'+ mtype.foto_type" alt="">
                    {{ mtype.desc_type }}
                </button>
            </div>
        </div>
        <div class="col-12 m-auto mt-5 d-flex flex-column align-items-center hidden">
            <img class="qr-code" :src="qrcod" alt="">
        </div>
        <div class="row">
            <!---<SearchComponent -search-placeholder="Search item here..." @add-to-cart="addToCart" @show-item="ShowItem"></SearchComponent>-->
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
                                <button class="btn add-btn p-1 px-2 py-1 show-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop" @click.prevent="ShowItem(item.id)">
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
                                <button class="btn add-btn p-1 px-2 py-1 show-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop" @click.prevent="ShowItem(item.id)">
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
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import SearchComponent from "../SearchComponent.vue";

export default {
    name: 'PublicMenu',

    components: {
        SearchComponent
    },

    data(){
        return {
            code_: 'https://api.qrserver.com/v1/',
            cod: 'create-qr-code/?size=150x150&data=',
            qrcod: null,
            publicMenuUrl: 'https://serge1997.github.io/portofolio/',
            MenuItems: null,
            show: null,
            MenuType: null,
            itemOfType: null,
            fiche: null
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

    mounted(){
        this.qrcod = `${this.code_}${this.cod}${this.publicMenuUrl}`
        this.getMenuType()



    }
}
</script>

<style scoped>
.qr-code {
    width: 320px;
    display: none;
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
.fantasy {
    font-family: 'Borel';
}
</style>
