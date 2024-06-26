<template>
    <div class="container">
        <template id="swal-template">
            <swal-html>
                Loading
            </swal-html>
        </template>
        <Button :label="`${$t('stockModals.delivery_title')}`" icon="pi pi-external-link" @click="visibleStockEntryModal = true" />
        <Dialog v-model:visible="visibleStockEntryModal" maximizable modal :header="`${$t('stockModals.delivery_title')}`" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-100">
                <div class="w-100 d-flex flex-column gap-2 mb-3">
                    <input type="hidden" v-model="stockEntry.requisition_id"/>
                    <label for="product-quantity">{{$t('forms.requisition_number')}}: </label>
                    <AutoComplete v-model="stockEntry.requisition_code" dropdown :suggestions="requisitionCode" @complete="searchRequisitionCode" @blur="getRequisitionProduct" placeholder="Digite o código da requisição"/>
                    <small class="text-danger" v-if="errMsg" v-for="requisition_code in errMsg.requisition_id" id="product-quantity-err"  v-text="requisition_code"></small>
                </div>
                <div class="w-100 m-auto d-flex flex-column align-items-center" v-if="requisitionProduct">
                    <DataTable class="w-100" :value="requisitionProduct" selectionMode="single"  paginator :rows="4" tableStyle="min-width: 50rem" edit-mode="row">
                        <Column field="prod_name" sortable style="width: 20%" header="Nome"></Column>
                        <Column field="quantity" sortable style="width: 10%" header="Quantidade"></Column>
                        <Column field="confirm_quantity" sortable style="width: 20%" header="Quantidade confirmado"></Column>
                        <Column field="sup_name" sortable style="width: 20%" header="Fornecedor"></Column>
                        <Column field="unitCost" sortable style="width: 20%" header="Ultimo preço de compra"></Column>
                        <Column header="Status" style="width: 25%">
                            <template class="w-100" #body="{ data }">
                                <Tag style="width: 90px" v-if="data.stat_desc === requisition_status.waiting" :value="data.stat_desc" severity="warning" />
                                <Tag style="width: 90px" v-else-if="data.stat_desc === requisition_status.rejected" :value="data.stat_desc" severity="danger" />
                                <Tag style="width: 90px" v-else severity="success" :value="data.stat_desc"/>
                            </template>
                        </Column>
                    </DataTable>
                    <div v-if="reqItemLoad" class="spinner-grow" role="status" style="width: 4rem; height: 4rem;">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <div class="w-100 d-flex flex-column gap-2">
                    <label for="product-name">{{$t('forms.product_name')}}</label>
                    <Dropdown @change="loadProductSupplier(stockEntry.productID)" :class="invalid" v-model="stockEntry.productID" :options="products" option-value="id" option-label="prod_name" placeholder="product"/>
                    <small class="text-danger" v-if="errMsg" v-for="prod_name in errMsg.productID" id="product-name-err"  v-text="prod_name"></small>
                </div>
                <div class="w-100 d-flex gap-2 mt-3">
                    <div class="d-flex flex-column w-50">
                        <label for="product-quantity">{{$t('forms.product_quantity')}}</label>
                        <InputText :class="invalid" type="number" id="product-quantity" v-model="stockEntry.quantity" aria-describedby="product-name" placeholder="product quantity"/>
                        <small class="text-danger" v-if="errMsg" v-for="quantity in errMsg.quantity" id="product-quantity-err"  v-text="quantity"></small>
                    </div>
                    <div class="d-flex flex-column w-50">
                        <label for="product-cost">{{$t('forms.product_cost')}}</label>
                        <InputText :class="invalid" type="number" id="product-cost" v-model="stockEntry.unitCost" aria-describedby="product-name" placeholder="product unit cost"/>
                        <small class="text-danger" v-if="errMsg" v-for="unitCost in errMsg.unitCost" id="product-unitCost-err"  v-text="unitCost"></small>
                    </div>
                </div>
                <div class="w-100 d-flex flex-column gap-2 mt-3">
                    <label for="supplier">{{$t('forms.placeholder_supplier')}}</label>
                    <Dropdown :class="invalid" v-model="stockEntry.supplierID" :options="suppliers" option-value="id" option-label="sup_name" placeholder="supplier"/>
                    <small class="text-danger" v-if="errMsg" v-for="sup_name in errMsg.supplierID" id="supplier-err"  v-text="sup_name"></small>
                </div>
            </div>
            <div class="w-100 d-flex justify-content-end p-3 mt-3">
                <Button @click="StoreStockEntry"  :label="`${$t('forms.submits.create')}`"/>
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
import AutoComplete from "primevue/autocomplete";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Tag from "primevue/tag"

export default{
    name: 'StockEntryComponent',

    components: {
        InputText,
        Dialog,
        Dropdown,
        AutoComplete,
        Button,
        DataTable,
        Column,
        Tag
    },

    data(){
        return {
            stockEntry: {
                productID: null,
                requisition_code: null,
                requisition_id: null,
                unitCost: null,
                quantity: null,
                supplierID: null
            },
            products: null,
            suppliers: null,
            errMsg: null,
            visibleStockEntryModal: false,
            invalid: null,
            requisitionCode: [],
            requisitionProduct: null,
            reqItemLoad: false,
            requisition_status:{
                waiting: "Pendente",
                approved: "Aprovado",
                rejected: "Recusado",
            },
            filtredSupplier: null,
            swalLoad: false,
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
                    this.invalid = ""
                    this.errMsg = null
            }).catch((errors) => {
                this.errMsg = errors.response.data.errors
                this.invalid = "p-invalid"
                errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'warning'}): null
            })
        },
        searchRequisitionCode(){
            let code = [];
            axios.get('/api/purchase-requisition/search/' + this.stockEntry.requisition_code).then((response) => {
                response.data.forEach(e => code.push(e.requisition_code));
                this.requisitionCode = [...code];
            })
        },

        getRequisitionProduct(){
               setTimeout(() => {
                    this.$swal.fire({
                    template: "#swal-template",
                    html:`
                            <div id="swal-load" style="height: 6rem;">
                                <div style="width: 3rem; height: 3rem; padding: 12px;" class="spinner-border" role="status">
                                </div>
                                <br>
                                <span class="">Aguarde...</span>
                            </div>
                            `,
                        showConfirmButton: false
                    })
                }, 1000)

            return new Promise(resolve => {
                setTimeout(() => {
                    axios.post('/api/purchase-requisition/filter-item', this.stockEntry)
                        .then(async(response) => {
                            this.requisitionProduct = response.data
                            const result = await response.data
                            this.products = await result.filter(product => product.is_delete !== 1);
                            let div = document.querySelector('.swal2-container');
                            div.remove()
                            resolve(true);
                            for (let req of response.data){
                                if (req.requisition_id !== null){
                                    this.stockEntry.requisition_id = req.requisition_id;
                                    break;
                                }
                            }
                        })
                        .catch(errors => console.log(errors))
                        .finally(() => {  })
                    }, 2000)
                })

        },
        loadProductSupplier(productID){
          axios.get(`/api/product-supplier/${productID}`).then((response) => {
              this.suppliers = [response.data];
              console.log(this.filtredSupplier)
          });



        },
        async loadSuppliers(){
            let supplierResult = await axios.get('/api/supplier');
            this.suppliers = await supplierResult.data;
        },

        mountedLoadEffect(){
            setTimeout(() => {
                this.$swal.fire({
                    template: "#swal-template",
                        html:`
                            <div id="swal-load" style="height: 6rem;">
                                <div style="width: 3rem; height: 3rem; padding: 12px;" class="spinner-border" role="status">
                                </div>
                                <br>
                                <span class="">Aguarde...</span>
                            </div>
                                `,
                        showConfirmButton: false
                    })
                }, 800)
            setTimeout(() => {
                let div = document.querySelector('.swal2-container');
                div.remove()
            }, 1800)
        }
    },

    async mounted(){
        this.mountedLoadEffect();
        this.loadSuppliers();
        let productResponse = await axios.get('/api/products');
        this.products = await productResponse.data
    }
}

</script>
