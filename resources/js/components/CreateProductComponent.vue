<template>
    <div class="container">
        <Button :label="`${$t('stockModals.product_title')}`" icon="pi pi-external-link" @click="visibleProductModal = true" />
        <Dialog v-model:visible="visibleProductModal" maximizable modal :header="`${$t('stockModals.product_title')}`" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="d-flex mt-3">
                <div class="w-100 d-flex flex-column gap-2">
                    <label for="product-name">{{$t('forms.product_name')}}</label>
                    <InputText :class="invalid" type="text" id="product-name" v-model="product.prod_name" aria-describedby="product-name" placeholder="product name"/>
                    <small class="text-danger" v-if="errMsg" v-for="prod_name in errMsg.prod_name" id="product-name-err"  v-text="prod_name"></small>
                </div>
                <div class="px-2"></div>
                <div class="w-100 d-flex flex-column gap-2">
                    <label for="prod-description">{{$t('forms.description')}}</label>
                    <InputText type="text" id="prod-desc" v-model="product.prod_desc" aria-describedby="item name" placeholder="product description"/>
                    <small class="text-danger" v-if="errMsg" v-for="item_name in errMsg.item_name" id="prod-desc-err"  v-text="item_name"></small>
                </div>
            </div>
            <div class="d-flex mt-3">
                <div class="w-100 d-flex flex-column gap-2">
                    <label for="unit-measure">{{$t('forms.measure_unit')}}</label>
                    <Dropdown v-model="product.prod_unmed" :class="invalid" option-value="un" :options="measure" optionLabel="un" placeholder="Select a food group" class="w-full md:w-14rem" />
                    <small class="text-danger" v-if="errMsg" v-for="prod_unmed in errMsg.prod_unmed" id="unit-measure-err"  v-text="prod_unmed"></small>
                </div>
                <div class="px-2"></div>
                <div class="w-100 d-flex flex-column gap-2">
                    <label for="item-name">{{$t('forms.unit_contain')}}</label>
                    <InputText :class="invalid" type="number" id="product-contain" v-model="product.prod_contain" aria-describedby="item name" />
                    <small class="text-danger" v-if="errMsg" v-for="contain in errMsg.prod_contain" id="product-contain-err"  v-text="contain"></small>
                </div>
            </div>
            <div class="w-100 d-flex flex-column gap-2 mt-3">
                <label for="item-name">{{$t('forms.min_quantity')}}</label>
                <InputText :class="invalid" type="number" id="product-min" v-model="product.min_quantity" aria-describedby="product-min"  placeholder="minimum stock quantity"/>
                <small class="text-danger" v-if="errMsg" v-for="min in errMsg.min_quantity" id="product-min-err"  v-text="min"></small>
            </div>
            <div class="d-flex flex-column w-100 mt-4">
                <Dropdown v-model="product.prod_supplierID" option-value="id" :options="suppliers" optionLabel="sup_name" :placeholder="`${$t('forms.placeholder_supplier')}`" class="w-100 md:w-14rem" />
                <small class="text-danger" v-if="errMsg" v-for="supID_name in errMsg.prod_supplierID" id="item-name-err"  v-text="supID_name"></small>
            </div>
            <div class="dialog-footer mt-3 p-2">
                <Button @click="StoreProduct" :label="`${$t('forms.submits.create')}`" />
            </div>
        </Dialog>
    </div>
</template>
<script>
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Dialog from "primevue/dialog";
import _ from "lodash";
export default{
    name: "CreateProductComponent",
    components:{
        Dropdown,
        Button,
        InputText,
        Dialog
    },

    data(){
        return {
            product:{
                prod_desc: null,
                prod_name: null,
                prod_unmed: null,
                prod_contain: null,
                prod_supplierID: null,
                min_quantity: null
            },
            suppliers: null,
            errMsg: null,
            invalid: null,
            measure: [
                { un: 'cl' },
                { un: 'bt' },
                { un: 'kg' },
                { un: 'g' },
                { un: 'folha' }
            ],
            visibleProductModal: false
        }
    },

    watch: {
        suppliers: _.debounce(function (newSupplier) {
            this.getSupplier()
        }, 5000)
    },
    methods: {
        StoreProduct(){
            axios.post("/api/product", this.product).then((response) => {
                this.product.prod_contain = "";
                this.product.prod_desc = "";
                this.product.prod_name = "";
                this.product.prod_unmed = "";
                this.product.prod_supplierID = "";
                this.product.min_quantity = "";
                this.$toast.success(response.data)
                this.errMsg = null
                this.invalid = null
            }).catch((errors) => {
                this.errMsg = errors.response.data.errors
                this.invalid = "p-invalid";
                errors.response.status !== 500 ? "" : this.visibleProductModal = false
                errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'warning'}): null
            })
        },

        getSupplier(){
            axios.get('/api/supplier').then((response) => {
                this.suppliers = response.data
                console.log(response.data)
            }).catch((errors) => {
                console.log(errors)
            })
        }
    },

    mounted(){
        this.getSupplier()
        console.log(this.suppliers)
    }
}
</script>
