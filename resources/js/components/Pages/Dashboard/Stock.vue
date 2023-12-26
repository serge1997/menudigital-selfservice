<template>
    <div class="container-fluid">
        <div class="col-md-2 d-flex justify-content-between">
            <router-link class="btn border-0 p-0" :to="{name: 'OperadorPanel'}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="feather feather-corner-down-left">
                    <polyline points="9 10 4 15 9 20"></polyline><path d="M20 4v7a4 4 0 0 1-4 4H4"></path>
                </svg>
            </router-link>
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
        <DataTable ref="dt" :value="products" selectionMode="single"  paginator :rows="10" tableStyle="min-width: 50rem">
            <div class="position-absolute" :class="{ 'place': placeh}"></div>
            <template #header>
                <div style="text-align: left">
                    <Button icon="pi pi-external-link" label="Export" @click="exportCSV($event)" />
                </div>
            </template>
            <Column field="productID" sortable style="width: 25%" exportHeader="Product Code" header="Code"></Column>
            <Column field="prod_name" sortable style="width: 25%" header="Name"></Column>
            <Column field="unitCost" sortable style="width: 25%" header="Cost"></Column>
            <Column field="sup_name" sortable style="width: 25%" header="Supplier"></Column>
            <Column field="saldoFinal" sortable style="width: 25%" header="Quantity"></Column>
            <Column field="prod_unmed" header="Medida"></Column>
        </DataTable>
    </div>
</template>

<script>
import CreateProductComponent from '../../CreateProductComponent.vue';
import TechnicalSheetComponent from '../../TechnicalSheetComponent.vue';
import StockEntryComponent from '../../StockEntryComponent.vue';
import SupplierComponent from '../../SupplierComponent.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from "primevue/dialog";
import Button from "primevue/button"

export default {
    name: "Stock",

    components: {
        CreateProductComponent,
        TechnicalSheetComponent,
        StockEntryComponent,
        SupplierComponent,
        DataTable,
        Column,
        Dialog,
        Button
    },

    data(){
        return {
            products: null,
            placeh: true
        }
    },

    methods: {
        get_stock_stat(){
            return new Promise((resolve, reject) => {
                setTimeout(() =>{
                    axios.get('/api/products-stat').then((response) => {
                        console.log(response.data)
                        this.products = response.data
                        this.placeh = false;
                    }).catch((errors) => {
                        console.log(errors)
                    })
                }, 1000)
            })
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
        }
    },

    mounted(){
        this.get_stock_stat();
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
    height: 400px;
    overflow: scroll;
}
</style>
