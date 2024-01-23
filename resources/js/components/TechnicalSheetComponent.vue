<template>
    <div class="container">
        <Button label="Create technical sheet" icon="pi pi-external-link" @click="visibleTechninalShettModal = true" />
        <Dialog v-model:visible="visibleTechninalShettModal" maximizable modal header="Create a technical sheet" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-100 mt-3">
                <div class="w-100 d-flex flex-column gap-2">
                    <div class="w-100 d-flex justify-content-center">
                        <div class="w-75">
                            <label for="prod-description">Menu item</label>
                            <Dropdown v-model="fiche.itemID" option-value="id" :options="menuItems" optionLabel="item_name" placeholder="Select user function" class="w-100 md:w-14rem" />
                            <small v-if="errMsg" v-for="msg in errMsg.itemID" class="text-danger p-1"> {{ msg }} </small>
                        </div>
                        <div class="p-4">
                            <Button label="Add field" @click="AddInputField" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-100 d-flex justify-content-center" v-for="(field, index) in incrementInput">
                <div class="d-flex flex-column w-50">
                    <label for="product">Product {{ index === 0 ? index + 1:index}}</label>
                    <Dropdown id="product" v-model="fiche.productID[index]" option-value="id" :options="products" optionLabel="prod_name" placeholder="Select a product" class="md:w-14rem" />
                    <small v-if="errMsg" v-for="msg in errMsg.productID" class="text-danger p-1"> {{ msg }} </small>
                </div>
                <div class="w-50 p-4">
                    <InputText class="w-100" v-model="fiche.quantity[index]" placeholder="product quantity"/>
                    <small v-if="errMsg" v-for="msg in errMsg.quantity" class="text-danger p-1"> {{ msg }} </small>
                </div>
                <div class="mt-4">
                    <Button @click="DeleteInputField(index)" icon="pi pi-times" severity="danger" rounded arial-label="cancel" />
                </div>
            </div>
            <div class="w-100 d-flex justify-content-end mt-3 p-2">
                <Button @click="storeTechnicalFiche" label="Save" />
            </div>
        </Dialog>
    </div>
</template>
<script>
import axios from 'axios';
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Dropdown from "primevue/dropdown";
import Dialog from "primevue/dialog";

export default{
    name: "TechnicalSheetComponent",
    components: {
        InputText,
        Button,
        Dropdown,
        Dialog
    },

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
            errMsg: null,
            visibleTechninalShettModal: false
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
                for (let index = 0; index < this.incrementInput.length; index++){
                    this.fiche.productID.splice(index, 1)
                    this.fiche.quantity.splice(index, 1)
                }
                this.$toast.success(response.data)
                this.fiche.itemID = ""
                this.incrementInput = []
            }).catch((errors) => {
                this.errMsg = errors.response.data.errors
                errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'warning'}): null
            })
        },
        async loadMenuItems(){
          let menuResponse = await axios.get('/api/menu-items');
          this.menuItems = await menuResponse.data
        },

        async loadProducts(){
          let productResponse = await axios.get('/api/products');
          this.products = await productResponse.data
        },
        async loadSuppliers(){
          let supplierResult = await axios.get('/api/supplier');
          this.suppliers = await supplierResult.data;
        },
    },

    async mounted(){
        await this.loadSuppliers();
        await this.loadProducts();
        await this.loadMenuItems();
    }
}
</script>
