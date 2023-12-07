<template>
    <div class="container">
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
            <button class="btn col-3 shadow p-3 border rounded-0 d-flex flex-column align-items-center justify-content-between" data-bs-toggle="modal" data-bs-target="#ModalCreateProduct">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0275d8"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
                <span class="py-2 fw-bold text-capitalize">New product</span>
            </button>
            <button class="btn col-3 shadow p-3 border rounded-0 d-flex flex-column align-items-center justify-content-between" data-bs-toggle="modal" data-bs-target="#ModalTechnicalSheet">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0275d8"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8">
                    </polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line>
                </svg>
                <span class="py-2 fw-bold text-capitalize">technical sheet</span>
            </button>
            <button class="btn col-3 shadow p-3 border rounded-0 d-flex flex-column align-items-center justify-content-between" data-bs-toggle="modal" data-bs-target="#ModalStockEntry">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0275d8"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder-plus">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                    <line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line>
                </svg>
                <span class="py-2 fw-bold text-capitalize">Stock entry</span>
            </button>
            <button class="btn col-3 shadow p-3 border rounded-0 d-flex flex-column align-items-center justify-content-between" data-bs-toggle="modal" data-bs-target="#ModalSupplier">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0275d8"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package"><line x1="16.5"
                    y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                    </path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
                <span class="py-2 fw-bold text-capitalize">Supplier</span>
            </button>
        </div>
        <div class="w-100 p-1 mt-2">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Produc ID</th>
                    <th>Product Name</th>
                    <th>Custo</th>
                    <th>Supplier Name</th>
                    <th>Saldo</th>
                    <th>Ultima Entrada</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="w-100 border shadow" id="stock-table">
            <table class="table table-striped">
                <div class="position-absolute" :class="{ 'place': placeh}"></div>
                <tbody>
                    <tr v-for="stat in prod_stat">
                        <td>{{ stat.productID }}</td>
                        <td>{{ stat.prod_name }}</td>
                        <td>{{ stat.unitCost }}</td>
                        <td>{{ stat.sup_name }}</td>
                        <td>{{ stat.saldoFinal }} {{ stat.prod_unmed }}</td>
                        <td>{{ stat.emissao }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <CreateProductComponent />
    <TechnicalSheetComponent />
    <StockEntryComponent />
    <SupplierComponent />
</template>

<script>
import CreateProductComponent from '../../CreateProductComponent.vue';
import TechnicalSheetComponent from '../../TechnicalSheetComponent.vue';
import StockEntryComponent from '../../StockEntryComponent.vue';
import SupplierComponent from '../../SupplierComponent.vue';
export default {
    name: "Stock",

    components: {
        CreateProductComponent,
        TechnicalSheetComponent,
        StockEntryComponent,
        SupplierComponent
    },

    data(){
        return {
            prod_stat: null,
            placeh: true
        }
    },

    methods: {
        get_stock_stat(){
            return new Promise((resolve, reject) => {
                setTimeout(() =>{
                    axios.get('/api/products-stat').then((response) => {
                        console.log(response.data)
                        this.prod_stat = response.data
                        this.placeh = false;
                    }).catch((errors) => {
                        console.log(errors)
                    })
                }, 1000)
            })
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
