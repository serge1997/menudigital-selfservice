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
                        <div class="px-2 py-2 rounded-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
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
                    <Button label="Manage"  data-bs-toggle="modal" data-bs-target="#ModelPersonalSetting"></Button>
                </div>
            </div>
            <div class="col-12 d-flex mt-5">
                <div class="col-lg-6 col-md-12">
                    <div class="header d-flex align-items-center">
                        <div class="px-2 py-2 rounded-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
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
                    <Button data-bs-toggle="modal" data-bs-target="#ModelItemSetting" label="Menu Alteration"></Button>
                </div>
            </div>
            <div class="col-12 d-flex mt-5">
                <div class="col-6">
                    <div class="header d-flex align-items-center">
                        <div class="px-2 py-2 rounded-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
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
                        <Button label="General configuration" data-bs-toggle="modal" data-bs-target="#ModalRestaurantinfo"></Button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ModalSohwFiche" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title text-capitalize"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="w-100">
                            <div class="w-100 d-flex justify-content-center gap-4 mb-4">
                                <Button icon="pi pi-pencil" text class="border rounded" id="edit-form" :disabled="fichebtn.editDisable" @click="showUpdateFicheForm"/>
                                <Button icon="pi pi-plus" class="border rounded" id="add-form" :disabled="fichebtn.addDisable" text @click="ShowAddNewElementFieldToFiche"/>
                            </div>
                            <table class="table table-bordered table-hover">
                                <thead class="" v-if="showfiche">
                                    <tr>
                                        <th class="text-capitalize fw-medium" v-for="item in ficheItem_name">{{ item }}</th>
                                        <th class="text-secondary fw-medium bg-fiche">Quantidade</th>
                                        <th class="text-secondary fw-medium bg-fiche">Custo</th>
                                        <th class="text-secondary fw-medium bg-fiche">Margem perda</th>
                                        <th class="text-secondary fw-medium bg-fiche">Margem fixo</th>
                                        <th class="text-secondary fw-medium bg-fiche">Margem variavel</th>
                                        <th class="text-secondary fw-medium bg-fiche">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="fiche in showfiche">
                                        <td>{{ fiche.prod_name }}</td>
                                        <td>{{ fiche.quantity + '  ' + fiche.prod_unmed}}</td>
                                        <td>{{fiche.cost + ' ' + 'R$' }}</td>
                                        <td>{{ fiche.loss_margin }} <small>R$</small></td>
                                        <td>{{ fiche.fix_margin }} </td>
                                        <td>{{ fiche.variable_margin }} </td>
                                        <th class="fw-medium"><Badge :value="fiche.total + ' R$'" /></th>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="1" class="fw-medium">Total</th>
                                        <th colspan="1" class="fw-medium">{{ totalContain }} Cl</th>
                                        <th colspan="1" class="fw-medium">{{ costData.productTotalcost }} R$</th>
                                        <th colspan="1" class="fw-medium">{{ costData.fix_margin }} R$</th>
                                        <th colspan="1" class="fw-medium">{{ costData.fix_margin }} R$</th>
                                        <th colspan="1" class="fw-medium">{{ costData.fix_margin }} R$</th>
                                        <th colspan="1" class="fw-medium"><Badge severity="success" :value="totalCost + ' R$'" /></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div v-if="showUpdateForm" class="w-100">
                            <div class="d-flex w-100 mb-3" v-for="item in showfiche">
                                <input type="hidden" class="product-ids" :value="item.productID"/>
                                <InputText type="text" class="w-50"  placeholder="product" :value="item.prod_name"/>
                                <span class="px-2"></span>
                                <InputText type="text" class="w-50 product-quantitys" placeholder="Quantity" :value="item.quantity"/>
                                <Button @click="deleteProductFromItemFiche(item.itemID, item.productID)" icon="pi pi-trash" class="text-danger" text />
                            </div>
                        </div>
                    </div>
                    <div v-if="showNewElementForm" class="w-100">
                            <div v-for="(input, index) in incrementInput" class="w-100 d-flex justify-content-center mb-3">
                                <select class="w-50 form-control p-2" placeholder="product" v-model="AddFicheNewData.productID[index]">
                                    <option selected>Selecione produto</option>
                                    <option :value="product.id" v-for="product in products">{{ product.prod_name }}</option>
                                </select>
                                <span class="px-2"></span>
                                <InputText type="number" class="w-25" placeholder="quantity" v-model="AddFicheNewData.quantity[index]"/>
                                <Button text icon="pi pi-plus" @click="incrementInputFieldUpdate"/>
                                <Button text icon="pi pi-trash" class="text-danger" @click="DeleteInputField(index)"/>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <Button v-if="showNewElementForm" @click="addNewItemToItemFiche" type="button" label="Adicionar"/>
                        <Button v-if="showUpdateForm" @click="EditFicheProductQuantity" type="button" label="Salvar edição"/>
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
import { nextTick } from 'vue'
import axios from 'axios';
import SettingMenuComponent from '../../SettingMenuComponent.vue';
import SettingPersonalComponent from '../../SettingPersonalComponent.vue';
import SettingRestaurantInfo from '../../SettingRestaurantInfo.vue';
import Badge from "primevue/badge";
import Button from "primevue/button";
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Dialog from 'primevue/dialog';

export default{
    name: 'SettingPanel',

    components: {
        SettingMenuComponent,
        SettingPersonalComponent,
        SettingRestaurantInfo,
        Badge,
        Button,
        InputText,
        Dropdown,
        Dialog
    },

    data(){
        return{
            MenuItems: null,
            showfiche: null,
            ficheItem_name: null,
            ficheItemID: null,
            totalContain: null,
            totalCost: 0,
            costData: {
                loss_margin: null,
                fix_margin: null,
                variable_margin: null,
                productTotalcost: null
            },
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
            fichebtn: {
                addDisable: null,
                editDisable: null
            },
            products: null
        }
    },
    methods: {
        showTechnicalFiche(id){
            axios.get(`/api/technical-fiche/${id}`).then( async (response) => {
                console.log(response.data)
                nextTick();
                let result = await response.data
                if (result.length >= 1){
                    this.showfiche = await response.data
                }else{
                    this.showfiche = false
                }
                let total = 0;
                let cost = 0;
                let contain = 0;
                let fix_margin = 0;
                let variable_margin = 0;
                let loss_margin = 0;
                let item_name = new Set();
                for (let fiche of this.showfiche){
                    total += Number(fiche.total)
                    contain += Number(fiche.quantity);
                    fix_margin += Number(fiche.fix_margin);
                    loss_margin += Number(fiche.loss_margin);
                    variable_margin += Number(fiche.variable_margin);
                    cost += Number(fiche.cost);
                    item_name.add(fiche.item_name)
                    this.totalCost = total.toFixed(2)
                    this.ficheItem_name = item_name
                    this.totalContain = contain;
                    this.AddFicheNewData.itemID = fiche.itemID;
                    this.UpdateFicheData = fiche.itemID;
                }
                this.costData.fix_margin = fix_margin.toFixed(2);
                this.costData.variable_margin = variable_margin.toFixed(2)
                this.costData.loss_margin = loss_margin.toFixed(2)
                this.costData.productTotalcost = cost.toFixed(2);
            }).catch((errors) => {
                console.log(errors)
            })
        },
        ShowAddNewElementFieldToFiche(){
            let btn_edit = document.getElementById('edit-form')
            this.showNewElementForm = !this.showNewElementForm
            if (this.incrementInput.length < 1){
                this.incrementInput.push(" ")
                this.showNewElementForm = true
            }
            this.fichebtn.editDisable = this.showNewElementForm == true ? "disabled" : btn_edit.removeAttribute("disabled");
        },
        showUpdateFicheForm(){
            let btn_add = document.getElementById('add-form')
            this.showUpdateForm = !this.showUpdateForm
            this.fichebtn.addDisable = this.showUpdateForm == true ? "disabled" : btn_add.removeAttribute("disabled");
        },

        incrementInputFieldUpdate(){
            this.incrementInput.push("");
        },
        DeleteInputField(index){
            this.incrementInput.splice(index, 1);
            this.AddFicheNewData.productID.splice(index, 1);
            this.AddFicheNewData.quantity.splice(index, 1);
        },

        async getAllProducts(){
            const apiProductResponse = await axios.get('/api/products');
            this.products = await apiProductResponse.data;
        },
        addNewItemToItemFiche(){
            axios.put('/api/fiche-menu-item', this.AddFicheNewData).then((response) => {
                this.$toast.success(response.data)
                this.showNewElementForm = false
                this.incrementInput = []
                return this.showTechnicalFiche(this.AddFicheNewData.itemID)
            }).catch(errors => {
                errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'warning'}): null
            })
        },

        EditFicheProductQuantity(){
            let productsInput = document.querySelectorAll('.product-ids');
            let quantitysInput = document.querySelectorAll('.product-quantitys')
            let quantitys = [].map.call(quantitysInput, value => value.value);
            let productIds = [].map.call(productsInput, idval => idval.value);
            let mountData = {
                itemId: this.AddFicheNewData.itemID,
                products: productIds,
                quantity: quantitys
            };

           axios.post('/api/fiche-menu-itens/edit-quantity', mountData).then((response) => {
                console.log(response.data)
                this.$toast.success(response.data)
                this.showUpdateForm = false
                return this.showTechnicalFiche(mountData.itemId);
           }).catch(errors => {
                errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'warning'}): null
           })
        },

        deleteProductFromItemFiche(itemID, productID){
            this.$swal.fire({
                text: "Está sendo deletando o produto da ficha técnica. clique em ok para confirmar",
                showCancelButton: true,
                icon: 'warning'
            }).then(result => {
                if (result.isConfirmed){
                    axios.delete(`/api/fiche-menu-itens/products/${itemID}/${productID}`).then((response) => {
                        console.log(response.data)
                        this.$toast.success(response.data);
                        return this.showTechnicalFiche(itemID);
                    }).catch(errors => {
                        errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'warning'}): null
                    })
                }
            })
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

        this.getAllProducts();
    },
}
</script>
<style scoped>
    .bg-fiche{
        background-color: #f2f2f2;
    }
</style>
