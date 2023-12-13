<template>
    <div class="container">
        <div class="modal fade" id="ModalTechnicalSheet" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-header p-2 text-capitalize shadow-lg rounded-3 text-white w-100">
                            <h6>create a item technical fiche </h6>
                            <p>Technical fiche</p>
                        </div>
                        <div class="w-100 mt-4">
                            <span v-if="errMsg" v-for="msg in errMsg.itemID" class="text-danger p-1"> {{ msg }} </span>
                            <div class="input-group p-1 mt-1">
                                <select class="form-select rounded-0 border border-secondary" v-model="fiche.itemID">
                                    <option selected value="">Menu items</option>
                                    <option v-for="item in menuItems" :value="item.id">{{ item.item_name }}</option>
                                </select>
                                <button class="btn bg-dark text-white rounded-0" @click="AddInputField">Add filed</button>
                            </div>
                            <div class="w-100 d-flex justify-content-between">
                                <div class="w-50 d-flex justify-content-start">
                                    <p v-if="errMsg" v-for="msg in errMsg.itemID" class="text-danger p-1"> {{ msg }} </p>
                                </div>
                                <div class="w-50 d-flex justify-content-start">
                                    <p v-if="errMsg" v-for="msg in errMsg.itemID" class="text-danger p-1"> {{ msg }} </p>
                                </div>
                            </div>
                            <div class="input-group mt-2" v-for="(field, index) in incrementInput">
                                <select class="form-select rounded-0 border border-secondary" v-model="fiche.productID[index]">
                                    <option selected>Select product</option>
                                    <option v-for="product in products" :value="product.id">{{ product.prod_name }}</option>
                                </select>
                                <span class="px-3"></span>
                                <input type="number" class="form-control rounded-0 border-secondary" placeholder="product quantity" v-model="fiche.quantity[index]">
                                <button @click="DeleteInputField(index)" class="btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2">
                                        </rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="storeTechnicalFiche" type="button" class="btn bg-dark text-white rounded-0">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';

export default{

    name: "TechnicalSheetComponent",

    data(){
        return{
            incrementInput: [],
            fiche: {
                itemID: null,
                productID: [],
                quantity: []
            },
            menuItems: null,
            products: null,
            errMsg: null
        }
    },

    methods:{
        AddInputField(){
            this.incrementInput.push("");
        },

        DeleteInputField(index){
            this.incrementInput.splice(index, 1);
            this.fiche.productID.splice(index, 1)
            this.fiche.quantity.splice(index, 1)
        },

        storeTechnicalFiche(){
            axios.post('/api/technical-fiche', this.fiche).then((response) => {
                if (response.data.status == 400)
                    this.$toast.error(response.data.msg)
                else{
                    this.$toast.success(response.data.msg)
                }
                for (let index = 0; index < this.incrementInput.length; index++){
                    this.fiche.productID.splice(index, 1)
                    this.fiche.quantity.splice(index, 1)
                }
                this.fiche.itemID = ""
                this.incrementInput = []
            }).catch((errors) => {
                this.errMsg = errors.response.data.errors
            })
        }
    },

    mounted(){
        axios.get('/api/menu/items').then((response) => {
            this.menuItems = response.data.items
            console.log(this.publicMenu)
        }).catch((errors) => {
            console.log(errors)
        })

        axios.get('/api/products').then((response) => {
            this.products = response.data.products
            this.suppliers = response.data.suppliers
        }).catch((errors) => {
            console.log(errors)
        })
    }
}
</script>
