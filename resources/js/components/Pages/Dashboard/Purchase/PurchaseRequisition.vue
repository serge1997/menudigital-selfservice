<template>
    <div class="container-fluid">
        <SideBarComponent />
        <div class="col-md-10 m-auto p-4">
            <Toolbar class="w-100">
                <template #start>
                    <div>
                        <PurchaseRequisitionEdition />
                    </div>
                </template>
                <template #end>
                    <div>
                        <Button @click="visibleNewPurchaseModal= true" label="Nova requisição" icon="pi pi-plus"/>
                    </div>
                </template>
            </Toolbar>
            <Dialog v-model:visible="visibleNewPurchaseModal" maximizable modal header="Nova requisição de compra" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <div class="w-100 mt-3">
                    <div class="w-100 m-auto d-flex justify-content-between align-items-center">
                        <h6>Requerente: {{ user.name }}</h6>
                       <div class="w-50">
                           <Dropdown v-model="purchaseData.department_id" :options="departments" option-value="id" option-label="name" placeholder="Selecione o departamento" class="w-100 md:w-14rem" />
                       </div>
                       <Calendar v-model="purchaseData.delivery_date" showIcon placeholder="Data entrega desejada"/>
                    </div>
                </div>
                <div class="w-100 d-flex flex-column align-items-center gap-2 mt-4">
                    <Button @click="incrementProductInput.push('')" icon="pi pi-plus" label="Adicionar produto" />
                   <div v-for="(field, index) in incrementProductInput" class="w-100 d-flex justify-content-center gap-2 mt-4">
                       <Dropdown v-model="purchaseData.products_id[index]" :options="products" option-value="id" option-label="prod_name" placeholder="Selecione o produto" class="w-100 md:w-14rem" />
                       <InputText class="col-md-5" type="number" v-model="purchaseData.products_quantity[index]"  placeholder="Quantidade"/>
                       <Button icon="pi pi-plus" text @click="incrementProductInput.push('')" />
                       <Button icon="pi pi-trash" style="color: red" text @click="decrementProductInput(index)" />
                   </div>
                </div>
                <div class="w-100 p-3 mt-3 d-flex justify-content-end">
                    <Button v-if="incrementProductInput.length >= 1" @click="createPurchaseRequisition" label="Submeter" />
                </div>
            </Dialog>
        </div>
        <div class="col-md-10 m-auto p-4">
        </div>
    </div>
</template>
<script>
import SideBarComponent from "../SideBarComponent.vue";
import PurchaseRequisitionEdition from "./PurchaseRequisitionEdition.vue";
import authuser from "./../../auth.js";
import Toolbar from "primevue/toolbar";
import InputText from "primevue/inputtext";
import Dialog from "primevue/dialog";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Dropdown from "primevue/dropdown";
import Calendar from "primevue/calendar";

export default {
    name: 'PurchaseRequisition',

    components: {
        SideBarComponent,
        PurchaseRequisitionEdition,
        Toolbar,
        Dialog,
        Column,
        InputText,
        DataTable,
        Button,
        Dropdown,
        Calendar
    },

    data(){
        return {
            visibleNewPurchaseModal: false,
            departments: null,
            purchaseData: {
                department_id: null,
                user_id: null,
                delivery_date: null,
                products_id: [],
                products_quantity: []
            },
            incrementProductInput: [],
            products: null,
            user: {
                name: null,
                id: null
            }
        }
    },
    methods: {
        createPurchaseRequisition(){
            console.log(this.purchaseData)
        },
        decrementProductInput(index){
            this.purchaseData.products_quantity.splice(index, 1);
            this.purchaseData.products_id.splice(index, 1);
            this.incrementProductInput.splice(index, 1);
        }
    },
    mounted(){
        axios.get('/api/products').then((response) => {
            this.products = response.data.products
            this.suppliers = response.data.suppliers
        }).catch((errors) => {
            console.log(errors)
        });

        axios.get('/api/group').then((response) => {
            this.departments = response.data.departments;
        }).catch((error) => {
            console.log(error)
        })

        authuser.then(result => { this.user.name = result.name});
    }
}
</script>
