<template>
    <div class="container">
        <div class="w-100">
            <router-link class="btn border border-secondary px-2 py-1" :to="{name: 'OperadorPanel'}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="feather feather-corner-down-left">
                    <polyline points="9 10 4 15 9 20"></polyline><path d="M20 4v7a4 4 0 0 1-4 4H4"></path>
                </svg>
            </router-link>
        </div>
        <div class="d-flex p-2 mt-3">
            <span class="p-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
            </span>
            <h3 class="px-2">Setting</h3>
        </div>
        <div class="row p-4 mt-4">
            <div class="col-12 d-flex">
                <div class="col-lg-6 col-md-12">
                    <div class="header d-flex align-items-center">
                        <div class="border px-2 py-2 rounded-1 modal-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63958"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h6 class="px-2">Employe management</h6>
                    </div>
                    <div class="col-md-12">
                        <small>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                            magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                        </small>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 d-flex justify-content-center align-items-center">
                    <button class="setting-btn rounded-0 alert p-2" data-bs-toggle="modal" data-bs-target="#ModelPersonalSetting">Manage</button>
                </div>
            </div>
            <div class="col-12 d-flex mt-5">
                <div class="col-lg-6 col-md-12">
                    <div class="header d-flex align-items-center">
                        <div class="border px-2 py-2 rounded-1 modal-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63958" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                        </div>
                        <h6 class="px-2">Ajuste menu</h6>
                    </div>
                    <small>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                    </small>
                </div>
                <div class="col-lg-6 col-md-12 d-flex justify-content-center align-items-center">
                    <button class="setting-btn p-2" data-bs-toggle="modal" data-bs-target="#ModelItemSetting">Menu Alteration</button>
                </div>
            </div>
            <div class="col-12 d-flex mt-5">
                <div class="col-6">
                    <div class="header d-flex align-items-center">
                        <div class="border px-2 py-2 rounded-1 modal-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63958" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-archive"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line>
                            </svg>
                        </div>
                        <h6 class="px-2">General information Configuration</h6>
                    </div>

                    <small>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                    </small>
                    <div class="col-6 d-flex justify-content-start mt-2 align-items-center">
                        <button class="setting-btn rounded-0 p-2" data-bs-toggle="modal" data-bs-target="#ModalRestaurantinfo">configuration</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ModalSohwFiche" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title text-capitalize">Show technical fiche</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    {{ UpdateFicheData.itemID }}
                    {{ UpdateFicheData.productID}}
                    {{ UpdateFicheData.quantity }}
                    <div class="modal-body">
                        <div class="w-100">
                            <table class="table table-striped">
                                <thead class="">
                                    <tr>
                                        <th class="text-uppercase" v-for="item in ficheItem_name">{{ item }}</th>
                                        <th class="bg-white">
                                            <button class="btn m-auto disabled" @click="showUpdateFicheForm">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                                </svg>
                                            </button>
                                        </th>
                                        <th>
                                            <button class="btn disabled" @click="ShowAddNewElementFieldToFiche">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square"><rect x="3" y="3"
                                                    width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line>
                                                </svg>
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="fiche in showfiche" class="text-capitalize">
                                        <td><span class="fw-medium">{{ fiche.prod_name }}</span></td>
                                        <td><span class="fw-medium">{{  fiche.quantity }} </span> {{ fiche.prod_unmed }}</td>
                                        <td><span class="fw-medium">{{ fiche.cost }}</span> R$</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="1">Total</th>
                                        <th class="bg-dark text-white" colspan="1">{{ totalContain }} Cl</th>
                                        <th class="bg-dark text-white" colspan="1">{{ totalCost }} R$</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div v-if="showUpdateForm" class="w-100">
                            <div class="input-group mt-2" v-for="item in showfiche">
                                <input type="hidden" class="name" :value="item.productID">
                                <input type="text" class="form-control rounded-0 border-secondary" placeholder="product" :value="item.prod_name">
                                <span class="px-2"></span>
                                <input type="text" class="form-control rounded-0 border-secondary quantity" placeholder="Quantity" :value="item.quantity">
                            </div>
                        </div>
                    </div>
                    <div v-if="showNewElementForm" class="w-100">
                            <div v-for="(input, index) in incrementInput" class="input-group mt-2 p-2">
                                <select class="form-select rounded-0 border-secondary" v-model="AddFicheNewData.productID[index]">
                                    <option value="Product Name">Product Name</option>
                                    <option v-for="product in products" :value="product.id">{{ product.prod_name }}</option>
                                </select>
                                <span class="px-2"></span>
                                <input type="text" class="form-control rounded-0 border-secondary" placeholder="product" v-model="AddFicheNewData.quantity[index]">
                                <button class="btn bg-dark text-white rounded-0" @click="incrementInputFieldUpdate">Add field</button>
                                <button class="btn" @click="DeleteInputField(index)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2">
                                        </rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button @click="UpdateFiche" type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <SettingMenuComponent @show-technical-fiche="showTechnicalFiche"/>
        <SettingPersonalComponent />
        <setting-restaurant-info></setting-restaurant-info>

    </div>
</template>

<script>
import axios from 'axios';
import SettingMenuComponent from '../../SettingMenuComponent.vue';
import SettingPersonalComponent from '../../SettingPersonalComponent.vue';
import SettingRestaurantInfo from '../../SettingRestaurantInfo.vue';

export default{
    name: 'SettingPanel',

    components: {
        SettingMenuComponent,
        SettingPersonalComponent,
        SettingRestaurantInfo
    },

    data(){
        return{
            MenuItems: null,
            showfiche: null,
            ficheItem_name: null,
            ficheItemID: null,
            totalContain: null,
            totalCost: 0,
            incrementInput: [""],
            AddFicheNewData: {
                itemID: null,
                productID: [],
                quantity: []
            },
            UpdateFicheData: {
                itemID: null,
                productID: [],
                quantity: []
            },
            showUpdateForm: false,
            showNewElementForm: false,
            products: null
        }
    },
    methods: {
        showTechnicalFiche(id){
            axios.get(`/api/technical-fiche/${id}`).then((response) => {
                console.log(response.data)
                this.showfiche = response.data
                let cost = 0;
                let contain = 0;
                let item_name = new Set();
                for (let fiche of this.showfiche){
                    cost += Number(fiche.cost)
                    contain += Number(fiche.quantity);
                    item_name.add(fiche.item_name)
                    this.totalCost = cost.toFixed(2)
                    this.ficheItem_name = item_name
                    this.totalContain = contain;
                    this.AddFicheNewData.itemID = fiche.itemID;
                    this.UpdateFicheData = fiche.itemID;
                }
            }).catch((errors) => {
                console.log(errors)
            })
        },
        ShowAddNewElementFieldToFiche(){
            this.showNewElementForm = !this.showNewElementForm
            if (this.incrementInput.length < 1){
                this.incrementInput.push(" ")
                this.showNewElementForm = true
            }
        },
        showUpdateFicheForm(){
            this.showUpdateForm = !this.showUpdateForm
        },

        incrementInputFieldUpdate(){
            this.incrementInput.push("");
        },
        DeleteInputField(index){
            this.incrementInput.splice(index, 1);
            this.AddFicheNewData.productID.splice(index, 1);
            this.AddFicheNewData.quantity.splice(index, 1);
        },

        UpdateFiche(){

        }
    },
    mounted() {
        axios.get('/api/menu/items').then((response) => {
            this.MenuItems = response.data.items
            console.log(response.data.items)
            this.load = false
        }).catch((errors) => {
            console.log(errors)
        })

        axios.get('/api/products').then((response) => {
            this.products = response.data.products
        }).catch((errors) => {
            console.log(errors)
        })
    },
}

</script>
