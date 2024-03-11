<template>
    <div class="container-fluid">
        <SideBarComponent />
        <div class="col-md-2 d-flex justify-content-between">
            <h6 class="mt-5 p-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database">
                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                </svg>
                <span class="px-2">Stock page</span>
            </h6>
        </div>
        <div class=" d-flex justify-content-center">
            <CreateProductComponent />
            <TechnicalSheetComponent />
            <StockEntryComponent />
            <SupplierComponent />
        </div>
        <DataTable id="stock-table" :value="products" selectionMode="single"  paginator :rows="10" tableStyle="min-width: 50rem">
            <div class="position-absolute" :class="{ 'place': placeh}"></div>
            <template #header>
                <div class="d-flex justify-content-between">
                    <div style="text-align: left">
                        <Button icon="pi pi-external-link" label="Export" @click="exportCSV($event)" />
                    </div>
                    <div class="d-flex gap-2">
                        <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText v-model="search_param" placeholder="produto, fornecedor" @input="filterDataTable" />
                        </span>
                    </div>
                </div>
            </template>
            <Column field="productID" sortable style="width: 10%" exportHeader="Product Code" header="Code"></Column>
            <Column field="prod_name" sortable style="width: 25%" header="Name"></Column>
            <Column field="unitCost" sortable style="width: 10%" header="Cost"></Column>
            <Column field="sup_name" sortable style="width: 25%" header="Supplier"></Column>
            <Column field="saldoFinal" sortable style="width: 10%" header="Quantity"></Column>
            <Column header="Status" style="width: 15%">
                <template class="w-100" #body="{ data }">
                    <Tag style="width: 90px" v-if="data.saldoFinal < data.min_quantity && data.saldoFinal > 0" value="Lowstock" severity="warning" />
                    <Tag style="width: 90px" v-else-if="data.saldoFinal == 0" value="ruptured" severity="danger" />
                    <Tag style="width: 90px" v-else value="In Stock" severity="success" />
                </template>
            </Column>
            <Column v-if="managerAccess.includes(`${auth.position_id}`)" field="prod_unmed" header="Ação" style="width: 25%">
                <template #body="{ data }">
                    <div class="d-flex">
                        <Button @click="showProductToEdit(data.productID)" icon="pi pi-pencil" text/>
                        <Button @click="deleteProduct(data.productID)" icon="pi pi-trash" text/>
                    </div>
                </template>
            </Column>
        </DataTable>
        <div class="row">
            <Dialog v-model:visible="visibleEditProductModal" maximizable modal header="Edit product information" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <div class="d-flex mt-3">
                    <div class="w-100 d-flex flex-column gap-2">
                        <input type="hidden" id="prod-id" :value="product_edit.id" />
                        <label for="prod-name">Product name</label>
                        <InputText :class="invalid" type="text" id="prod-name" :value="product_edit.prod_name" aria-describedby="product-name" placeholder="product name"/>
                        <small class="text-danger" v-if="errMsg" v-for="prod_name in errMsg.prod_name" id="product-name-err"  v-text="prod_name"></small>
                    </div>
                    <div class="px-2"></div>
                    <div class="w-100 d-flex flex-column gap-2">
                        <label for="prod-desc">Product description</label>
                        <InputText type="text" id="prod-desc" v-model="product_edit.prod_desc" aria-describedby="item name" placeholder="product description"/>
                        <small class="text-danger" v-if="errMsg" v-for="item_name in errMsg.item_name" id="prod-desc-err"  v-text="item_name"></small>
                    </div>
                </div>
                <div class="d-flex mt-3">
                    <div class="w-100 d-flex flex-column gap-2">
                        <label for="prod-contain">Unit contain</label>
                        <InputText :class="invalid" type="number" id="prod-contain" :value="product_edit.prod_contain" aria-describedby="item name" />
                        <small class="text-danger" v-if="errMsg" v-for="contain in errMsg.prod_contain" id="product-contain-err"  v-text="contain"></small>
                    </div>
                </div>
                <div class="w-100 d-flex flex-column gap-2 mt-3">
                    <label for="prod-min">Minimum quantity</label>
                    <InputText :class="invalid" type="number" id="prod-min" :value="product_edit.min_quantity" aria-describedby="product-min"  placeholder="minimum stock quantity"/>
                    <small class="text-danger" v-if="errMsg" v-for="min in errMsg.min_quantity" id="product-min-err"  v-text="min"></small>
                </div>
                <div class="d-flex flex-column w-100 mt-4">
                    <Dropdown v-model="product.prod_supplierID" option-value="id" :options="suppliers" optionLabel="sup_name" placeholder="Select a supplier" class="w-100 md:w-14rem" />
                    <small class="text-danger" v-if="errMsg" v-for="supID_name in errMsg.prod_supplierID" id="prod-supplier-err"  v-text="supID_name"></small>
                </div>
                <div class="dialog-footer mt-3 p-2">
                    <Button @click="editProduct" label="Save product edition" />
                </div>
            </Dialog>
        </div>
    </div>
</template>

<script>
import CreateProductComponent from '../../CreateProductComponent.vue';
import TechnicalSheetComponent from '../../TechnicalSheetComponent.vue';
import StockEntryComponent from '../../StockEntryComponent.vue';
import SupplierComponent from '../../SupplierComponent.vue';
import SideBarComponent from "./SideBarComponent.vue";
import DataTable from 'primevue/datatable';
import Dropdown from "primevue/dropdown";
import Column from 'primevue/column';
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import Tag from "primevue/tag";
import InputText from "primevue/inputtext";
import { getAuth } from '../auth';
import { randTime } from '../../../rand';
import _ from "lodash";

export default {
    name: "Stock",

    components: {
        CreateProductComponent,
        TechnicalSheetComponent,
        StockEntryComponent,
        SupplierComponent,
        SideBarComponent,
        DataTable,
        Column,
        Dialog,
        Button,
        Tag,
        InputText,
        Dropdown
    },

    data(){
        return {
            products: null,
            visibleEditProductModal: false,
            placeh: true,
            product_edit: null,
            suppliers: null,
            search_param: null,
            product:{
                id: null,
                prod_desc: null,
                prod_name: null,
                prod_contain: null,
                prod_supplierID: null,
                min_quantity: null
            },
            auth: {
                id: null,
                position_id: null,
            },
            errMsg: null,
            managerAccess: localStorage.getItem('managerAccess').split(','),
        }
    },

    watch: {
        products: _.debounce(function (newProducts){
            this.get_stock_stat();
        }, 4000)
    },

    methods: {
        get_stock_stat(){
           if (this.search_param == null){
            return new Promise((resolve, reject) => {
                setTimeout(() =>{
                    axios.get('/api/products-stat').then((response) => {
                        console.log(response.data)
                        this.products = response.data
                        this.placeh = false;
                    }).catch((errors) => {
                        console.log(errors)
                    })
                }, randTime())
            })
           }
        },

        convertToCSV(objArray) {
            const array = typeof objArray !== 'object' ? JSON.parse(objArray) : objArray;
            let csv = '';
            const headers = Object.keys(array[0]).join(',') + '\n';

            csv += headers;
            array.forEach((item) => {
                let row = '';
                Object.values(item).forEach((value) => {
                    if (row !== '') row += ',';
                    row += `"${value}"`;
                });
                csv += row + '\n';
            });

            return csv;
        },

        exportCSV(){
            const fields = ['Product code', 'Product name', 'Cost', 'Supplier name', 'Saldo', 'Medida'];
            const header = { fields };

            try{
                const csv = this.convertToCSV(this.products);

                const blob = new Blob([csv], {type: '\'text/csv;charset=utf-8;\';'});
                const link = document.createElement('a');

                if (link.download !== undefined){
                    const url = URL.createObjectURL(blob);
                    link.setAttribute('href', url);
                    link.setAttribute('download', 'products.csv');
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }

            }catch (error) {
                console.error(error);
            }
        },
        showProductToEdit(id){
            axios.get(`/api/product/${id}`).then((response) => {
                this.visibleEditProductModal = true
               this.product_edit = response.data
                console.log(this.product_edit)
            }).catch((errors) => {
                console.log(errors);
            })
        },

        editProduct(){
            this.product.id           = document.getElementById('prod-id').value;
            this.product.prod_name    = document.getElementById('prod-name').value;
            this.product.prod_desc    = document.getElementById('prod-desc').value;
            this.product.prod_contain = document.getElementById('prod-contain').value;
            this.product.min_quantity = document.getElementById('prod-min').value;
            axios.put('/api/product', this.product).then((response) => {
                this.visibleEditProductModal = false
                this.$swal.fire({
                    text: response.data,
                    icon: 'success'
                })
            }).catch((errors) => {
                this.visibleEditProductModal = false
                errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'error'}) : '';
                console.log(errors)
            })
        },

        deleteProduct(id){
            this.$swal.fire({
                html: '' +
                    '<p>Quer apagar esse produto ?</p>' + '' +
                    '<p>Após apagar esse produto não irá ser mais disponivel<br>para qualquer serviço</p>',
                icon: "question",
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed){
                    axios.delete(`/api/product/${id}`).then((response) => {
                        this.$swal.fire({
                            text: response.data,
                            icon: 'success'
                        })
                    }).catch((errors) =>{
                        errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'error'}) : '';
                        console.log(errors)
                    })
                }
            })
        },

        filterDataTable(){
            this.axios.get('/api/stock-search/', {params: {search_param: this.search_param}}).then((response) => {
                this.products = response.data
            })
        },
        async loadSupplier(){
            let response = await axios.get('/api/supplier')
            this.suppliers = await response.data;
        }
    },

    mounted(){
        this.get_stock_stat();
        this.loadSupplier();
        getAuth().then(result => {
            this.auth.position_id = result.position_id
        })
    }
}

</script>
<style scoped>
.place {
    width: 81.3%;
    height: 300px;
    background-image: url('./../../../../../public/img/placeholder.png');
    animation: place 1s infinite;
}

@keyframes place {
    0% {background-color: #777;}
    10% {opacity: 0.8;}
    30% {opacity: 0.6;}
}

#stock-table {
    min-height: 600px;
    height: 700px;
    overflow: scroll;
}
</style>
