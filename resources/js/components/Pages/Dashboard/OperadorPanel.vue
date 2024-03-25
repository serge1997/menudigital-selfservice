<template>
     <SideBarComponent></SideBarComponent>
    <div class="col-lg-12 col-md-12">
        <template id="swal-template">
            <swal-html>
                Loading
            </swal-html>
        </template>
        <div class="d-flex justify-content-between p-0 z-0">
            <div class="col-8 m-auto">
                <h4 class="">{{ $t('operator.title') }}</h4>
                <div class="py-4">
                    <h5 class="text-center fw-normal text-capitalize">{{ $t('operator.table_box_title') }}</h5>
                    <div class="col-lg-10 col-md-12 m-auto d-flex justify-content-center flex-wrap p-2 mt-2">
                        <button  v-for="tab in tables" class="btn border-0 col-lg-3 col-md-4">
                            <div class="col-sm-10 d-flex flex-column border p-2">
                                <img class="col-sm-5 img-thumbnail border-0" src="/img/free.png" />
                                <Tag severity="success" :value ="`${$t('operator.table')} ${tab.table}`"/>
                            </div>
                        </button>
                       <button v-for="busy in busyTables" class="col-lg-3 col-md-4 btn border-0">
                             <div class="col-sm-10 d-flex flex-column border p-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <img class="col-sm-5 img-thumbnail border-0" src="/img/busy.png" />
                                    <Badge severity="warning" :value="busy.customer " />
                                </div>
                                <small class="text-left">{{busy.name}}</small>
                                <Tag severity="danger" :value ="`Mesa ${busy.ped_tableNumber} (${busy.timing})`"/>
                            </div>
                       </button>
                    </div>
                </div>
                <div class="w-100 py-2 d-flex">
                    <Toolbar class="w-100">
                        <template #start>
                            <Button :label="`${$t('operator.toolbar.one')}`" @click="this.$router.push('/home')" icon="pi pi-plus" class="" />
                            <!--<Button label="inventory" data-bs-toggle="modal" icon="pi pi-eye" data-bs-target="#InventoryModal" @click.prevent="getOrderItem(pedido.id)" class="mr-2" />-->
                            <InventoryComponent/>
                            <SellReportComponent/>
                            <BillHistoriyComponent :status="status" />
                        </template>
                    </Toolbar>
                </div>
               <table class="table border">
                    <thead>
                        <tr>
                            <th class="text-capitalize">Id</th>
                            <th class="text-capitalize">{{ $t('operator.dataTable.two')}}</th>
                            <th class="text-capitalize">{{ $t('operator.dataTable.three')}}</th>
                            <th class="text-capitalize">{{ $t('operator.dataTable.four')}}</th>
                            <th class="text-capitalize">Status</th>
                            <th class="text-capitalize">{{ $t('operator.dataTable.six')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border alert-bg" v-for="pedido in order" :key="pedido.id">
                            <td v-if="pedido.status_id == 6" class="alert-bg">{{ pedido.id }}</td>
                            <td v-else class="success-bg">{{ pedido.id }}</td>
                            <td v-if="pedido.status_id == 6" class="alert-bg">{{ pedido.ped_customerName }}</td>
                            <td v-else class="success-bg">{{ pedido.ped_customerName }}</td>
                            <td v-if="pedido.status_id == 6" class="alert-bg">{{ pedido.ped_tableNumber }}</td>
                            <td v-else class="success-bg">{{ pedido.ped_tableNumber }}</td>
                            <td v-if="pedido.status_id == 6" class="alert-bg">{{ pedido.total }}</td>
                            <td v-else class="success-bg">{{ pedido.total }}</td>
                            <td v-if="pedido.status_id == 6" class="alert-bg">{{ pedido.stat_desc }}</td>
                            <td v-else class="success-bg">{{ pedido.stat_desc }}</td>
                            <td class="d-flex jusitify-content-center align-items-center">
                                <Button title="Pagar"
                                      @click="getPaymentOrderItens(pedido.id)"
                                      icon="pi pi-money-bill" text
                                />
                                <Button
                                   data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                   @click.prevent="getOrderItem(pedido.id)"
                                   icon="pi pi-eye"
                                   text
                                />
                                <Button
                                    icon="pi pi-print"
                                    text
                                />
                                <div class="d-flex align-items-center">
                                    <OrderTransfertComponent @get-TransfertItems="getTransferItens(pedido.id)" :transfert-items="titems" :tables="tables"/>
                                </div>
                                <Button
                                    @click="setOrderID(pedido.id)" data-bs-toggle="modal"
                                    data-bs-target="#cancelorder" text
                                    icon="pi pi-trash"
                                    class="text-danger"
                                />
                            </td>
                        </tr>
                    </tbody>
               </table>
            </div>
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content rounded-0">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 text-capitalize" id="staticBackdropLabel">customer bill</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table w-100 table-active">
                                <thead>
                                    <tr class="text-capitalize">
                                        <th>Item</th>
                                        <th>quantidade</th>
                                        <th>Valor</th>
                                        <th>Subtotal</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in itens">
                                        <td>{{ item.item_name }}</td>
                                        <td>{{ item.item_quantidade }}</td>
                                        <td>{{ item.item_price }}</td>
                                        <td>{{ item.item_total }}</td>
                                        <td>
                                            <button v-if="item.status_id != 6" data-bs-toggle="modal" @click="StorParams(item.id, item.item_id)" data-bs-target="#cancel" class="btn border-0 disabled">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" stroke="#d9534f" stroke-width="1.3" stroke-linecap="round"
                                                    stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2">
                                                    </rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line>
                                                </svg>
                                            </button>
                                            <button v-else data-bs-toggle="modal" @click="StorParams(item.id, item.item_id)" data-bs-target="#cancel" class="btn border-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" stroke="#d9534f" stroke-width="1.3" stroke-linecap="round"
                                                    stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2">
                                                    </rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="fw-medium b fs-5">
                                        <td>Totais</td>
                                        <td>{{ billTotalItem }}</td>
                                        <td></td>
                                        <td class="text-left bg-dark text-white" colspan="2">
                                            {{ billTotal.toFixed(2) }} R$
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="cancel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content rounded-0 border-0">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Acesso restrito (cancel item)</h1>
                            <span class="px-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     stroke="#d9534f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon">
                                    <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
                                    <line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </span><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                        <div class="modal-body">
                            <p class="fs-5">Manager Password needed</p>
                            <div>
                                <form @submit.prevent="cancelOrderItem">
                                    <input class="form-control rounded-0 border-secondary" type="password" v-model="cancel.password" placeholder="password here...">
                                    <input class="form-control rounded-0 border-secondary mt-2" type="text" v-model="cancel.quantidade" placeholder="Quantidade">
                                    <div class="form-check form-switch mt-3">
                                        <input class="form-check-input border border-secondary" type="checkbox" id="flexSwitchCheckDefault" v-model="cancel.to_return">
                                        <label class="form-check-label fw-medium" for="flexSwitchCheckDefault">Com retorno em estoque ?</label>
                                    </div>
                                    <input class="btn rounded-0 border mt-4 w-50 bg-warning" data-bs-dismiss="modal" type="submit" value="Ok">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="cancelorder" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content rounded-0 border-0">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Acesso restrito (cancel order)</h1>
                            <span class="px-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     stroke="#d9534f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon">
                                    <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
                                    <line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </span><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                        <div class="modal-body">
                            <p class="fs-5">Manager Password needed</p>
                            <div>
                                <form @submit.prevent="CancelOrder">
                                    <input type="hidden" v-model="cancel.orderID"/>
                                    <input class="form-control rounded-0 border-secondary" type="password" v-model="cancel.password" placeholder="password here...">
                                    <input class="btn rounded-0 border mt-4 w-50 bg-warning" data-bs-dismiss="modal" type="submit" value="Ok">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="BillHistory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content rounded-0 border-0">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Acess To bill history</h1>
                            <span class="px-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     stroke="#d9534f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon">
                                    <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
                                    <line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </span><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                        <div class="modal-body">
                            <p class="fs-5">Manager Password needed</p>
                            <div>
                                <form @submit.prevent="CancelOrder">
                                    <input type="hidden" v-model="cancel.orderID"/>
                                    <input class="form-control rounded-0 border-secondary" type="password" v-model="cancel.password" placeholder="password here...">
                                    <input class="btn rounded-0 border mt-4 w-50 bg-warning" data-bs-dismiss="modal" type="submit" value="Ok">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="OrderStat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content rounded-0 border-0">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Acesso restrito</h1>
                            <span class="px-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     stroke="#d9534f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon">
                                    <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
                                    <line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </span>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="fs-5">Manager Password needed</p>
                            <div>
                                <form @submit.prevent="EditOrderStat">
                                    <input class="form-control rounded-0 border-secondary" type="password" v-model="orderStat.password" placeholder="password here...">
                                    <select class="form-select mt-3 border-secondary rounded-0" v-model="orderStat.status_id">
                                        <option v-for="stat in status" :value="stat.id">
                                            {{ stat.id }}
                                        </option>
                                    </select>
                                    <input class="btn rounded-0 border mt-4 w-50 bg-warning" data-bs-dismiss="modal" type="submit" value="Ok">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Dialog v-model:visible="visiblePaymentModal" maximizable modal header="Pagamento" :style="{ width: '80rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="row">
                <input type="hidden" v-model.trim="order_id" />
                <div class="col-md-5">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantidade</th>
                                <th>Preco</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in itens">
                                <td>{{ item.item_name }}</td>
                                <td>{{ item.item_quantidade }}</td>
                                <td>{{ item.item_price }}</td>
                                <td>{{ item.item_total }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="fw-medium b fs-5">
                                <td>Totais</td>
                                <td>{{ billTotalItem }}</td>
                                <td></td>
                                <td class="text-left" colspan="2">
                                    {{ billTotal.toFixed(2) }} R$
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="type-paiement col-md-6 d-flex flex-column">
                    <div>

                    </div>
                    <div class="d-flex justify-content-center gap-3 p-3 flex-wrap">
                        <div v-for="stat in status">
                            <Button style="width: 15rem;" class="p-3" :severity="severity_btn" @click="setOrderPaymentStatus(stat.id, order_id)">
                                <span>{{ stat.stat_desc }}</span>
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </Dialog>
    </div>
</template>

<script>
import axios from 'axios';
import SideBarComponent from './SideBarComponent.vue';
import OrderTransfertComponent from '../../OrderTransfertComponent.vue';
import SellReportComponent from '../../SellReportComponent.vue';
import InventoryComponent from "@/components/InventoryComponent.vue";
import BillHistoriyComponent from "@/components/BillHistoriyComponent.vue";
import _ from 'lodash'
import Toolbar from "primevue/toolbar";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dialog from 'primevue/dialog';
import Badge from 'primevue/badge';
import Tag from 'primevue/tag';
export default {
    name: 'OperadorPanel',

    components: {
        SideBarComponent,
        OrderTransfertComponent,
        SellReportComponent,
        BillHistoriyComponent,
        InventoryComponent,
        Toolbar,
        Button,
        InputText,
        Dialog,
        Badge,
        Tag
    },

    data() {
        return {
            say: "Hello",
            order: null,
            itens: null,
            status: null,
            linkSTyle: 'text-white',
            tables: null,
            busyTables: null,
            cancel: {
                password: null,
                quantidade: null,
                orderID: null,
                to_return: false,
            },
            cancelOrderID: null,
            item_id: null,
            item_pedido: null,
            orderStat: {
                password: null,
                status_id: null
            },
            order_id: null,
            titems: null,
            billTotal: 0,
            billTotalItem: null,
            visiblePaymentModal: false,
            severity_btn: null,
        }
    },
    watch:{
        order: _.debounce(function(newOrder){
            this.getOrder();
            this.getTable()
        }, 8000)
    },

    async created() {
       await this.getOrder();
       await this.getTable();
       //this.getCanceledStatus()

    },

    methods: {
       async getOrder() {
            let response = await  axios.get('/api/orders-status')
            this.order = await response.data.order
            this.status = await response.data.status
        },

        async getOrderItem(id) {
            this.billTotal = 0
            this.billTotalItem = 0
            let result = await axios.get('/api/order-menu-itens/' + id);
            this.itens = await result.data

            for (let bill of this.itens) {
                this.billTotal += Number(bill.item_total)
                this.billTotalItem += Number(bill.item_quantidade)
            }

        },

        setOrderPaymentStatus(status_id, order_id) {
           const data = {
                status: status_id,
                order: order_id
           }
           this.$swal.fire({
               icon: "question",
               text: "Do you want really to achieve this action",
               confirmButtonColor: '#333',
               confirmButtonText: 'Confirm',
               showCancelButton: true
           }).then((result) => {
               if (result.isConfirmed){
                   axios.put('/api/order-payment', data).then((response) => {
                       this.$toast.success(response.data)
                       this.visiblePaymentModal = !this.visiblePaymentModal;
                       return this.getOrder()
                   }).catch((errors) => {
                       console.log(errors.response.data.message)
                       errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'warning'}): null
                   })
               }
           })

        },

        async getTable() {
            try{
                let result = await axios.get('/api/tablenumbers-orders');
                this.tables = await result.data.tables
                this.busyTables = await result.data.busyTables
            }catch(errors) {
                console.log(errors);
            }

        },

        StorParams(OrderId, item_id){
            if (localStorage.getItem('OrderId')){
                localStorage.removeItem('OrderId');
            }
            if (localStorage.getItem('item_id')){
                localStorage.removeItem('item_id');
            }
            localStorage.setItem('OrderId', OrderId);
            localStorage.setItem('item_id', item_id);
            this.item_id = localStorage.getItem('item_id');
            this.item_pedido = localStorage.getItem('OrderId');
        },

        setOrderID(id){
           this.cancel.orderID = id;
        },

        cancelOrderItem(){
            const cancelData = {
                item_pedido: this.item_pedido,
                item_id: this.item_id,
                password: this.cancel.password,
                quantidade: this.cancel.quantidade,
                to_return: this.cancel.to_return,
            }
            axios.post(`/api/cancel/order-item`, cancelData).then((response) => {
                this.$toast.success(response.data)
                console.log(response.data)
                this.cancel.password = "";
                this.cancel.quantidade = "";
                this.cancel.to_return = false;
            }).catch((errors) => {
                errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'warning'}): null
            })
        },

        CancelOrder(){
           axios.put('/api/cancel-order', this.cancel).then((response) => {
               this.$toast.success(response.data)
               return this.getOrder();
           }).catch((errors) => {
               errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'warning'}): null;
           })
        },


        EditOrderStat(){
            axios.post(`/api/editorder/stat/${this.item_pedido}`, this.orderStat)
                .then((response) => {
                    if (response.data.statut === 200) {
                        this.$toast.success(response.data.msg)
                        this.orderStat.password = "";
                    }else{
                        if (response.data.statut === 404){
                            this.$toast.error(response.data.msg)
                        }
                    }

                }).catch((errors) => {
                    console.log(errors.response.data.errors.password)
                })
        },

        getTransferItens(id) {
            axios.get('/api/order-transfert-itens/' + id)
                .then((response) => {
                    this.titems = response.data
                    console.log(response.data)
                })
        },

        async getPaymentOrderItens(id){
            this.visiblePaymentModal = true;
            this.order_id = id;
            this.billTotal = 0
            this.billTotalItem = 0
            let result = await axios.get('/api/order-menu-itens/' + id);
            this.itens = await result.data

            for (let bill of this.itens) {
                this.billTotal += Number(bill.item_total)
                this.billTotalItem += Number(bill.item_quantidade)
            }

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
                }, 100)
            setTimeout(() => {
                let div = document.querySelector('.swal2-container');
                div.remove()
            }, 10800)
        },
        setSeverity(id){
            switch(id) {
                case 1 :
                    return this.severity_btn = "success";
                break;
                case 2 :
                    return this.severity_btn = "primary";
                break;
                default: null
            }
        }

    },

    mounted(){
        this.mountedLoadEffect();

    }
}

</script>
<style scoped>
    .position-fixed {
        max-width: 310px;
        width: 230px;
    }

    .alert-bg{
        background-color: #f8d7da;
    }

    .success-bg {
        background-color: #d4edda;
    }

</style>
