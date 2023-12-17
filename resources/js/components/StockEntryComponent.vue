<template>
    <div class="container">
        <Button label="Save a product delivery" icon="pi pi-external-link" @click="visibleStockEntryModal = true" />
        <Dialog v-model:visible="visibleStockEntryModal" maximizable modal header="Save product Delivery" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-100">
                <div class="w-100 d-flex flex-column gap-2">
                    <label for="product-name">Product name</label>
                    <Dropdown v-model="stockEntry.productID" :options="products" option-value="id" option-label="prod_name" placeholder="product"/>
                    <small class="text-danger" v-if="errMsg" v-for="prod_name in errMsg.productID" id="product-name-err"  v-text="prod_name"></small>
                </div>
                <div class="w-100 d-flex gap-2 mt-3">
                    <div class="d-flex flex-column w-50">
                        <label for="product-quantity">Product quantity</label>
                        <InputText :class="invalid" type="text" id="product-quantity" v-model="stockEntry.quantity" aria-describedby="product-name" placeholder="product quantity"/>
                        <small class="text-danger" v-if="errMsg" v-for="quantity in errMsg.quantity" id="product-quantity-err"  v-text="quantity"></small>
                    </div>
                    <div class="d-flex flex-column w-50">
                        <label for="product-cost">Cost</label>
                        <InputText :class="invalid" type="number" id="product-cost" v-model="stockEntry.unitCost" aria-describedby="product-name" placeholder="product unit cost"/>
                        <small class="text-danger" v-if="errMsg" v-for="unitCost in errMsg.unitCost" id="product-unitCost-err"  v-text="unitCost"></small>
                    </div>
                </div>
                <div class="w-100 d-flex flex-column gap-2 mt-3">
                    <label for="supplier">Supplier</label>
                    <Dropdown v-model="stockEntry.supplierID" :options="suppliers" option-value="id" option-label="sup_name" placeholder="supplier"/>
                    <small class="text-danger" v-if="errMsg" v-for="sup_name in errMsg.supplierID" id="supplier-err"  v-text="sup_name"></small>
                </div>
            </div>
            <div class="w-100 d-flex justify-content-end p-3 mt-3">
                <Button @click="StoreStockEntry"  label="Save"/>
            </div>
        </Dialog>
    </div>
</template>

<script>
import axios from 'axios'
import InputText from "primevue/inputtext";
import Dialog from "primevue/dialog";
import Dropdown from "primevue/dropdown";
import Button from "primevue/button";

export default{
    name: 'StockEntryComponent',

    components: {
        InputText,
        Dialog,
        Dropdown,
        Button
    },

    data(){
        return {
            stockEntry: {
                productID: null,
                unitCost: null,
                quantity: null,
                supplierID: null
            },
            products: null,
            suppliers: null,
            errMsg: null,
            visibleStockEntryModal: false,
            invalid: null
        }
    },

    methods: {
        StoreStockEntry(){
            axios.post('/api/stock-entry', this.stockEntry).then((response) => {
                    this.stockEntry.productID = "";
                    this.stockEntry.quantity = "";
                    this.stockEntry.supplierID = "";
                    this.stockEntry.unitCost = ""
                    this.$toast.success(response.data)
                    this.errMsg = null
            }).catch((errors) => {
                this.errMsg = errors.response.data.errors
                this.invalid = "p-invalid"
                errors.response.status === 400 ? this.$toast.error(errors.response.data): null
            })
        }
    },

    mounted(){
        axios.get('/api/products').then((response) => {
            this.products = response.data.products
            this.suppliers = response.data.suppliers
        }).catch((errors) => {
            console.log(errors)
        })
    }
}

</script>
