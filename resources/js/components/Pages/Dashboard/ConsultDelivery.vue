<template>
    <SideBarComponent />
    <div class="w-100">
        <div class="container-fluid">
            <div class="container p-0">
                <div class="row p-0">
                    <Toolbar class="w-100">
                        <template #start>
                            <div>
                                <h5 class="d-flex gap-3 align-items-center"><i class="pi pi-calendar"></i>{{ $t('delivery.title')}}</h5>
                            </div>
                        </template>
                        <template #end>
                            <div class="">
                                <Button @click="$router.push({name: 'Stock'})" icon="pi pi-database" :label="`${$t('purchase.toolbar.one')}`" text/>
                                <DeliveryDevolutionComponent />
                            </div>
                        </template>
                    </Toolbar>
                </div>
                <div class="w-100 mt-3">
                    <DataTable :value="deliveryList" selectionMode="single"  paginator :rows="10" tableStyle="min-width: 55rem">
                        <Column field="requisition_code" sortable style="width: 25%" :header="`${$t('delivery.dataTable.one')}`" />
                        <Column field="user_name" sortable style="width: 25%" :header="`${$t('purchase.dataTable.two')}`" />
                        <Column field="department_name" sortable style="width: 25%" :header="`${$t('forms.placeholder_dept')}`" />
                        <Column field="delivery_date" sortable style="width: 25%" :header="`${$t('biexpense.dataTable.six')}`" />
                        <Column :header="`${$t('bicost.dataTable.height')}`">
                            <template #body = "{ data }">
                                <div class="d-flex gap-2">
                                    <Button @click="showDeliveryItems(data.id)" icon="pi pi-eye" text />
                                    <Button @click="deleteDelivery(data.id)" icon="pi pi-trash" class="text-danger" text />
                                </div>
                            </template>
                        </Column>
                    </DataTable>
                </div>
                <div class="row">
                    <Dialog v-model:visible="visibleDeliveryItemsModal" @after-hide="showEditquantityInput = false" maximizable modal header="Produtos comprados" :style="{ width: '85rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                       <div class="col-md-12 m-auto">
                           <div v-if="showEditquantityInput" class="col-md-10 m-auto d-flex flex-column align-items-center gap-2 p-2">
                                <div class="w-100">
                                    <label>Informe a quantidade a devolver: </label>
                                </div>
                                <div class="w-100 d-flex gap-2">
                                    <InputText class="w-75" v-model="editQuantityData.quantity" placeholder="quantidade" />
                                    <input type="hidden" v-model="editQuantityData.product_id">
                                    <input type="hidden" v-model="editQuantityData.requisition_id" />
                                    <Button @click="editproductDeliveryQuantityAction" label="confirmar" />
                                </div>
                           </div>
                       </div>
                       <div class="w-100">
                            <div class="col-md-10 m-auto mb-3" v-if="load">
                                <p class="text-center">aguarde...</p>
                                <ProgressBar mode="indeterminate" style="height: 6px;" />
                            </div>
                            <DataTable :value="showItems" selectionMode="single"  paginator :rows="10" tableStyle="min-width: 55rem">
                                <Column field="prod_name" sortable style="width: 25%" header="Product" />
                                <Column field="unitCost" sortable style="width: 15%" header="Cost" />
                                <Column field="quantity" sortable style="width: 15%" header="Quantity" />
                                <Column field="totalCost" sortable style="width: 15%" header="Total" />
                                <Column field="supp_name" sortable style="width: 25%" header="Supplier" />
                                <Column header="Ação">
                                    <template #body="{ data }">
                                        <div class="d-flex gap-1">
                                            <Button @click="showEditProductDeliveryForm(data.requisition_id, data.productID)" icon="pi pi-pencil" text />
                                            <Button @click="deleteOneProduct(data.requisition_id, data.productID)" icon="pi pi-trash" class="text-danger" text />
                                        </div>
                                    </template>
                                </Column>
                            </DataTable>
                       </div>
                    </Dialog>
                </div>
            </div>
        </div>
        <Dialog v-model:visible="visibleDeleteDeliveryModal" maximizable modal header="" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="col-md-8 d-flex flex-column m-auto">
                <p class="text-center">{{ loadMsg }}</p>
                <ProgressBar v-if="visibleDeleteDeliveryModal" mode="indeterminate" style="height: 6px"></ProgressBar>
            </div>
        </Dialog>
    </div>
</template>
<script>
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Calendar from 'primevue/calendar';
import Toolbar from 'primevue/toolbar';
import SideBarComponent from './SideBarComponent.vue';
import DeliveryDevolutionComponent from '../../DeliveryDevolutionComponent.vue';
import ProgressBar from 'primevue/progressbar';
import Button from 'primevue/button';
import { randTime } from '../../../rand';
export default {
    name: 'ConsultDelivery',
    components: {
        DataTable,
        Calendar,
        Column,
        InputText,
        Toolbar,
        SideBarComponent,
        DeliveryDevolutionComponent,
        Button,
        Dialog,
        ProgressBar,
    },
    data(){
        return {
            deliveryList: null,
            visibleDeliveryItemsModal: false,
            visibleDeleteDeliveryModal: false,
            showItems: false,
            load: false,
            loadMsg: 'Atualizando saldo....',
            showEditquantityInput: false,
            editQuantityData: {
                requisition_id: null,
                product_id: null,
                quantity: null
            }
        }
    },
    methods: {
       async loadDelivery() {
            try{
                const deliveryResponse = await axios.get('/api/stock-entry');
                this.deliveryList = await deliveryResponse.data;
            }catch(errors) {
                console.log(errors)
            }
        },
        showDeliveryItems(id){
            this.load = true;
            this.visibleDeliveryItemsModal = true;
            this.showItems = null
            return new Promise(resolve => {
                setTimeout( async () => {
                    try{
                        const showResponse = await axios.get('/api/stock-requistion/' + id)
                        this.showItems = await showResponse.data;
                        this.load = false
                        resolve(true);
                    }catch(errors){
                        console.log(errors)
                    }finally{
                        this.load = false
                    }
                }, randTime())
            })
        },
        deleteDelivery(id){
            this.$swal.fire({
                text: 'Está sendo deletado uma entrega. clique em ok para continuar',
                icon: 'warning',
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed){
                    this.visibleDeleteDeliveryModal = true;
                    setTimeout(() => {this.loadMsg = 'Atualizando as entregas...'}, 800);
                    setTimeout(() => {this.loadMsg = "deletando a entrega..."}, 2000)
                     return new Promise(resolve => {
                        setTimeout(() => {
                            axios.delete('/api/stock-delivery/requisition/' + id)
                            .then((response) => {
                                this.$swal.fire({text: response.data, icon: 'success'});
                                this.loadDelivery()
                                resolve(true);
                            })
                            .catch(errors => {
                                errors.response.status === 500 && this.$swal.fire({
                                    text: errors.response.data,
                                    icon: 'error'
                                });
                            })
                            .finally(() => {
                                this.visibleDeleteDeliveryModal = false
                            })
                        }, 3000)
                     })
                }
            })
        },
        /**delete a single product from delivery group */
        deleteOneProduct(requisition_id, product_id){
            this.$swal.fire({
                text: 'Está sendo deletar um produto entregado. Clique em ok para continuar',
                icon: 'warning',
                showCancelButton: true
            })
            .then((result) => {
                if (result.isConfirmed){
                    this.visibleDeleteDeliveryModal = true;
                    setTimeout(() => {this.loadMsg = 'Atualizando as entregas...'}, 800);
                    setTimeout(() => {this.loadMsg = "deletando a entrega..."}, 2000)
                    return new Promise(resolve => {
                       setTimeout(() => {
                        axios.delete(`/api/stock-delivery/requisition/${requisition_id}/product/${product_id}`)
                        .then((response) => {
                            this.$swal.fire({text: response.data, icon: 'success'});
                            resolve(true);
                        })
                        .catch(errors => {
                            errors.response.status === 500 && this.$swal.fire({
                                    text: errors.response.data,
                                    icon: 'error'
                                });
                        })
                        .finally(() => {
                            this.visibleDeleteDeliveryModal = false;
                        })
                       }, 3000)
                    })
                }
            })
        },
        showEditProductDeliveryForm(requisition_id, product_id)
        {
           this.showEditquantityInput = true
           this.editQuantityData.requisition_id = requisition_id;
           this.editQuantityData.product_id = product_id;
        },
        editproductDeliveryQuantityAction(){
            return new Promise(resolve => {
                this.visibleDeleteDeliveryModal = true;
                setTimeout(() => {this.loadMsg = 'Atualizando as entregas...'}, 800);
                setTimeout(() => {this.loadMsg = "deletando a entrega..."}, 2000)
                setTimeout(() => {
                    axios.post('/api/stock-delivery/edit/product-quantity', this.editQuantityData).then((response) => {
                        this.$swal.fire({
                            text: response.data,
                            icon: 'success'
                        })
                        this.editQuantityData.quantity = null;
                        this.showEditquantityInput = false;
                    })
                    .catch(errors => {
                        errors.response.status === 500 && this.$swal.fire({text: errors.response.data, icon: 'error'});
                    })
                    .finally(() => {
                        this.visibleDeleteDeliveryModal = false;
                    })
                }, 3000)
            })
        }
    },
    mounted(){
        this.loadDelivery();
    }
}

</script>
