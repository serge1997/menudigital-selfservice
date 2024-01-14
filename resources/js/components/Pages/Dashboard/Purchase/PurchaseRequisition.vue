<template>
    <div class="container-fluid">
        <SideBarComponent />
        <div class="col-md-10 m-auto p-4">
            <Toolbar class="w-100">
                <template #start>
                    <div>

                    </div>
                </template>
                <template #end>
                    <div>
                        <Button @click="this.$router.push({name: 'Stock'})" label="Consultar estoque" icon="pi pi-database" text/>
                        <Button @click="visibleNewPurchaseModal= true" label="Nova requisição" icon="pi pi-plus"/>
                    </div>
                </template>
            </Toolbar>

            <Dialog v-model:visible="visibleNewPurchaseModal" maximizable modal header="Nova requisição de compra" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <div class="w-100 mt-3">
                    <div class="w-100 m-auto d-flex justify-content-between align-items-center">
                        <h6>Requerente: {{ user.name }}</h6>
                       <div class="w-50 d-flex flex-column">
                           <Dropdown v-model="purchaseData.department_id" :class="invalidInput" :options="departments" option-value="id" option-label="name" placeholder="Selecione o departamento" class="w-100 md:w-14rem" />
                           <small class="text-danger" v-if="errMsg" v-for="errdepartment in errMsg.department_id" v-text="errdepartment"></small>
                       </div>
                       <div class="w-25 d-flex flex-column">
                           <Calendar :class="invalidInput" v-model="purchaseData.delivery_date" showIcon placeholder="Data entrega desejada"/>
                           <small class="text-danger" v-if="errMsg" v-for="errdata in errMsg.delivery_date" v-text="errdata"></small>
                       </div>
                    </div>
                </div>
                <div class="w-100 d-flex flex-column align-items-center gap-2 mt-4">
                    <Button @click="incrementProductInput.push('')" icon="pi pi-plus" label="Adicionar produto" />
                   <div v-for="(field, index) in incrementProductInput" class="w-100 d-flex justify-content-center gap-2 mt-4">
                       <div class="col-md-5 d-flex flex-column">
                           <Dropdown :class="invalidInput" v-model="purchaseData.products_id[index]" :options="products" option-value="id" option-label="prod_name" placeholder="Selecione o produto" class="w-100 md:w-14rem" />
                           <small class="text-danger" v-if="errMsg" v-for="errproduct in errMsg.product_id" v-text="errproduct"></small>
                       </div>
                       <div class="col-md-5">
                           <InputText :class="invalidInput" class="w-100" type="number" v-model="purchaseData.quantity[index]"  placeholder="Quantidade"/>
                           <small class="text-danger" v-if="errMsg" v-for="errquantity in errMsg.quantity" v-text="errquantity"></small>
                       </div>
                       <Button icon="pi pi-plus" text @click="incrementProductInput.push('')" />
                       <Button icon="pi pi-trash" style="color: red" text @click="decrementProductInput(index)" />
                   </div>
                </div>
                <div class="w-100 p-3 mt-3 d-flex justify-content-end">
                    <Button v-if="incrementProductInput.length >= 1" @click="createPurchaseRequisition" label="Submeter" />
                </div>
            </Dialog>
        </div>
        <div class="col-md-12 m-auto p-4">
            <DataTable :value="requisitions" selectionMode="single"  paginator :rows="10" tableStyle="min-width: 50rem">
                <Column field="requisition_code" sortable style="width: 15%" header="Code"></Column>
                <Column field="require_name" sortable style="width: 20%" header="Requerente"></Column>
                <Column field="name" sortable style="width: 15%" header="Departamento"></Column>
                <Column field="created_at" sortable style="width: 15%" header="Emissao"></Column>
                <Column field="delivery_date" sortable style="width: 15%" header="A entregar"></Column>
                <Column header="Status" style="width: 25%">
                    <template class="w-100" #body="{ data }">
                        <Tag style="width: 90px" v-if="data.stat_desc === requisition_status.waiting" :value="data.stat_desc" severity="warning" />
                        <Tag style="width: 90px" v-else-if="data.stat_desc === requisition_status.rejected" :value="data.stat_desc" severity="danger" />
                        <Tag style="width: 90px" v-else severity="success" :value="data.stat_desc"/>
                    </template>
                </Column>
                <Column field="prod_unmed" header="Status" style="width: 25%">
                    <template #body="{ data }">
                        <div class="d-flex">
                            <div>
                                <PurchaseRequisitionEdition :id="data.id" :status_desc="data.stat_desc" />
                            </div>
                            <div>
                                <ShowRequisition :id="data.id"/>
                            </div>
                            <div>
                                <Button @click="deleteRequisition(data.id)" style="color: red" icon="pi pi-trash" text/>
                            </div>

                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
</template>
<script>
import SideBarComponent from "../SideBarComponent.vue";
import PurchaseRequisitionEdition from "./PurchaseRequisitionEdition.vue";
import ShowRequisition from "./ShowRequisition.vue";
import authuser from "./../../auth.js";
import Toolbar from "primevue/toolbar";
import InputText from "primevue/inputtext";
import Dialog from "primevue/dialog";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Dropdown from "primevue/dropdown";
import Calendar from "primevue/calendar";
import Tag from "primevue/tag";
import _ from "lodash";

export default {
    name: 'PurchaseRequisition',

    components: {
        SideBarComponent,
        PurchaseRequisitionEdition,
        ShowRequisition,
        Toolbar,
        Dialog,
        Column,
        InputText,
        DataTable,
        Button,
        Dropdown,
        Calendar,
        Tag
    },
    watch: {
        requisitions: _.debounce(function(newRequisition){
            this.index()
        }, 10000)
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
                quantity: []
            },
            incrementProductInput: [],
            products: null,
            user: {
                name: null,
                id: null
            },
            errMsg: null,
            invalidInput: null,
            requisitions: null,
            requisition_status:{
                waiting: "Pendente",
                approved: "Aprovado",
                rejected: "Recusado",
            }
        }
    },
    methods: {
        createPurchaseRequisition(){
            axios.post('/api/purchase-requisition', this.purchaseData).then((response) => {
                console.log(response.data)
                this.$swal.fire({
                    text: response.data,
                    icon: 'success'
                })
                this.invalidInput = ''
                this.visibleNewPurchaseModal = false
                this.purchaseData.quantity = [];
                this.purchaseData.products_id = []
                this.purchaseData.delivery_date = "";
                this.errMsg = "";
            }).catch((errors) => {
                this.invalidInput = 'p-invalid';
                this.errMsg = errors.response.data.errors
                console.log(errors)
            })
        },
        decrementProductInput(index){
            this.purchaseData.quantity.splice(index, 1);
            this.purchaseData.products_id.splice(index, 1);
            this.incrementProductInput.splice(index, 1);
        },

        index(){
            axios.get('/api/purchase-requisition').then((response) => {
                console.log(response.data);
                this.requisitions = response.data
            }).catch((errors) => {
                console.log(errors)
            })
        },
        deleteRequisition(id){
            this.$swal.fire({
                text: "Quer realmente apagar esta requisição ?",
                icon: 'question',
                showCancelButton: true,
            }).then(result => {
                if (result.isConfirmed){
                    axios.delete(`/api/purchase-requisition/${id}`).then(response => {
                        console.log(response.data);
                        this.$swal.fire({
                            text: response.data,
                            icon: 'success'
                        })
                        return this.index();
                    }).catch(errors => {
                        console.log(errors);
                        errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'warning' }): null;
                    })
                }
            })
        },
        showRequisition(id){

        }
    },
    mounted(){
        this.index();
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

        authuser.then(result => { this.user.name = result.name; this.purchaseData.user_id = result.id});
    }
}
</script>
