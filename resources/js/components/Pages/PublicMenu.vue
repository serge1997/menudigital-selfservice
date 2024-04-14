<template>
    <div class="container">
        <div class="w-100 fantasy p-3 d-flex flex-column align-items-center">
            <h6 class="fs-2">Casino bar</h6>
            <h6 class="fs-6">Menu digital</h6>
        </div>
        <div class="row d-flex justify-content-center shadow-sm">
          <div class="row d-flex justify-content-center p-3">
            <div class="col-lg-2 col-md-2 d-flex justify-content-center menu-type-card p-2" v-for="mtype in MenuType" :key="mtype.id_type">
              <button class="btn w-50 border-0 d-flex flex-column align-items-center justify-content-center text-capitalize fw-medium" @click.prevent="getItemOfType(mtype.id_type)">
                <img style="width: 60%;" class="rounded-circle border type-btn" :src="'/img/type/'+ mtype.foto_type" alt="">
                {{ mtype.desc_type }}
              </button>
            </div>
          </div>
        </div>
        <div class="row mb-4 mt-2">
            <div class="col-md-8 d-flex flex-column align-items-center mb-3 m-auto">
                <img class="qr-code" :src="qrcod" alt="">
            </div>
        </div>
        <div class="row p-4">
            <div v-if="!itemOfType" v-for="item in MenuItems" :key="item.id" class="col-lg-3 col-md-10 mb-4 m-auto">
                <div class="card rounded-0 border-0 p-0 w-75 m-auto">
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
                      <div class="text-white d-flex justify-content-end mt-1">
                        <Button icon="pi pi-eye" @click="ShowItem(item.id)" />
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <div v-else v-for="item in itemOfType" class="col-lg-3 col-md-10 mb-4 m-auto">
              <div class="card rounded-0 border-0 p-0 w-75 m-auto">
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
                    <div class="text-white d-flex justify-content-end mt-1">
                      <Button icon="pi pi-eye" @click="ShowItem(item.id)" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>

        <div class="row">
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
        </div>
    </div>
</template>
<script>
import SearchComponent from "../SearchComponent.vue";
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import ProgressBar from "primevue/progressbar";
import {randTime} from "@/rand.js";
import axios from "axios";

export default {
    name: 'PublicMenu',

    components: {
      SearchComponent,
      Button,
      Dialog,
      ProgressBar
    },

    data(){
        return {
            code_: 'https://api.qrserver.com/v1/',
            cod: 'create-qr-code/?size=150x150&data=',
            qrcod: null,
            publicMenuUrl: location.href,
            MenuItems: null,
            show: null,
            MenuType: null,
            itemOfType: null,
            fiche: null,
            visibleShowItemMenuModal: false,
            loadBar: false
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

      async ShowItem(id){
        this.loadBar = true;
        this.show = null;
        this.fiche = null;
        this.visibleShowItemMenuModal = true;
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
    },

    mounted(){
        this.qrcod = `${this.code_}${this.cod}${this.publicMenuUrl}`
        this.getMenuType()



    }
}
</script>

<style scoped>
.qr-code {
    width: 220px;
}


.menu-type-card:hover .type-btn{
    background-color: #e63958;
}
.fantasy {
    font-family: 'Borel';
}
</style>
