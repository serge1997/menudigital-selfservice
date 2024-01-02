<template>
    <div class="container">
        <Button label="Create a supplier register" icon="pi pi-external-link" @click="visibleSupplierModal = true" />
        <Dialog v-model:visible="visibleSupplierModal" maximizable modal header="Create a new supplier" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-100 py-2">
                <Button @click="showForm = !showForm" label="New" icon="pi pi-plus"></Button>
            </div>
            <div v-if="showForm">
                <div class="w-100 d-flex gap-2 mt-3">
                    <div class="w-50 d-flex flex-column gap-2">
                        <label for="sup-name">Supplier name</label>
                        <input type="hidden" v-model="supplier.id"/>
                        <InputText :class="invalid" type="text" id="sup-name" v-model="supplier.sup_name" aria-describedby="product-name" placeholder="product name"/>
                        <small class="text-danger" v-if="errMsg" v-for="sup_name in errMsg.sup_name" id="product-name-err"  v-text="sup_name"></small>
                    </div>
                    <div class="w-50 d-flex flex-column gap-2">
                        <label for="sup-cpf">Supplier person id</label>
                        <InputText type="text" id="sup-cpf" v-model="supplier.sup_personID" aria-describedby="cpf" placeholder="cpf"/>
                    </div>
                </div>
                <div class="w-100 d-flex gap-2 mt-3">
                    <div class="d-flex flex-column w-50">
                        <label for="sup-tel">Contact (cel) </label>
                        <InputMask :class="invalid"  mask="(99)99 999-9999" type="text" id="product-quantity" v-model="supplier.sup_tel" aria-describedby="sup-tel" placeholder="(99)99 999-9999"/>
                        <small class="text-danger" v-if="errMsg" v-for="tel in errMsg.sup_tel" id="sup-tel-err"  v-text="tel"></small>
                    </div>
                    <div class="d-flex flex-column w-50">
                        <label for="sup-bairro">Bairro </label>
                        <InputText type="text" id="sup-bairro" v-model="supplier.sup_neighborhood" aria-describedby="product-name" placeholder="product neighborhood"/>
                    </div>
                </div>
                <div class="d-flex flex-column w-100 mt-3">
                    <label for="sup-email">Supplier e-mail </label>
                    <InputText type="text" id="sup-email" v-model="supplier.sup_email" aria-describedby="product-name" placeholder="supplier e-mail"/>
                </div>
                <div class="d-flex flex-column w-100 mt-3">
                    <label for="sup-city">Supplier city </label>
                    <InputText type="text" id="sup-city" v-model="supplier.sup_city" aria-describedby="product-name" placeholder="supplier city"/>
                </div>
                <div class="w-100 d-flex justify-content-end p-3 mt-3">
                    <Button @click="StoreSupplier" label="Save" />
                </div>
            </div>
            <div class="w-100">
                <DataTable ref="dt" :value="suppliers" selectionMode="single"  paginator :rows="10" tableStyle="min-width: 50rem" edit-mode="row">
                    <Column field="id" sortable style="width: 10%" header="Code"></Column>
                    <Column field="sup_name" sortable style="width: 10%" header="Name"></Column>
                    <Column field="sup_email" sortable style="width: 20%" header="Email"></Column>
                    <Column field="sup_tel" sortable style="width: 10%" header="Supplier Contact"></Column>
                    <Column header="Status" style="width: 10%">
                        <template #body="{ data }">
                            <div class="d-flex">
                                <Button @click="showSupplierToEdit(data.id)" icon="pi pi-pencil" text/>
                                <Button @click="deleteSupplier(data.id)" icon="pi pi-trash" text/>
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </Dialog>
    </div>
</template>

<script>
import axios from 'axios';
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import InputMask from "primevue/inputmask";
import DataTable from "primevue/datatable";
import Column from "primevue/column";

export default{
    name: 'SupplierComponent',
    computed: {
        show() {
            return show
        }
    },
    components: {
        Dialog,
        InputText,
        Button,
        InputMask,
        DataTable,
        Column
    },

    data() {
        return {
            supplier: {
                id: null,
                sup_name: null,
                sup_personID: null,
                sup_tel: null,
                sup_city: null,
                sup_neighborhood: null,
                sup_email: null
            },
            errMsg: null,
            visibleSupplierModal: false,
            invalid: null,
            suppliers: null,
            showForm: false,
            supplier_res: null
        }
    },
    methods: {
        StoreSupplier(){
            let method;
            this.supplier.id == null ? method = axios.post : method = axios.put;
            method('/api/supplier', this.supplier).then((response) => {
                this.supplier.sup_city = "";
                this.supplier.sup_email = "";
                this.supplier.sup_name = "";
                this.sup_neighborhood = "";
                this.supplier.sup_tel = "";
                this.supplier.sup_personID = "";
                this.$toast.success(response.data)
                this.errMsg = null
                this.invalid = null
                return this.getSuppliers()
            }).catch((errors) => {
                console.log(errors)
                this.errMsg = errors.response.data.errors
                this.invalid = "p-invalid"
                errors.response.status === 400 ? this.$toast.error(errors.response.data) :null
            })
        },
        showSupplierToEdit(id){
            axios.get(`/api/supplier/${id}`).then((response) => {
                this.supplier = response.data
                this.showForm = true
            }).catch((errors) => {
                console.log(errors)
            })
        },

        deleteSupplier(id){
            this.visibleSupplierModal = false;
            this.$swal.fire({
                text: "You want to delete this supplier ?",
                icon: "question",
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed){
                    axios.delete(`/api/supplier/${id}`).then((response) => {
                        this.$swal.fire({
                            text: response.data,
                            icon: 'success'
                        })
                        return this.getSuppliers()
                    }).catch((errors) => {
                        console.log(errors)
                    })
                }
            })
        },
        getSuppliers(){
            axios.get('/api/supplier').then((response) => {
                this.suppliers = response.data
            })
        }
    },
    mounted(){
        this.getSuppliers()
    }
}

</script>
