<template>
    <div class="col-lg-12 col-md-12">
        <SideBarComponent></SideBarComponent>
        <div class="d-flex justify-content-between p-0 z-0">
            <div class="col-8 m-auto">
                <h4 class="text-capitalize">Espaço operador</h4>
                <div class="py-4">
                    <h5 class="text-center fw-normal text-capitalize">Ocupação mesa na sala</h5>
                    <div class="d-flex justify-content-center flex-wrap p-2 mt-2">
                        <div class="col-2 bg-success border p-2" v-for="tab in tables">
                            <h6 class="text-white text-center fw-normal">Mesa {{ tab.table }}<br><small>livre</small></h6>
                        </div>
                        <div class="col-2 bg-danger border p-2" v-for="busy in busyTables">
                            <h6 class="text-white text-center fw-normal">Mesa {{ busy.ped_tableNumber }}<br><small>ocupada</small><br><small>{{ busy.name }}</small></h6>
                        </div>
                    </div>
                </div>
                <div class="w-100 py-2 d-flex">
                    <Toolbar class="w-100">
                        <template #start>
                            <Button label="New order" @click="this.$router.push('/home')" icon="pi pi-plus" class="" />
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
                            <th class="text-capitalize">Nome</th>
                            <th class="text-capitalize">Mesa</th>
                            <th class="text-capitalize">Valor Total</th>
                            <th class="text-capitalize">Status</th>
                            <th class="text-capitalize">Ação</th>
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
                                <div class="btn-group">
                                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    </button>
                                    <ul class="dropdown-menu p-0 shadow">
                                        <h6 class="text-capitalize fw-medium p-2 bg-dark text-white">pagamento</h6>
                                        <div class="">
                                            <li v-for="stat in status">
                                                <button class="btn dropdown-btn" @click="UpdateOrderStatus(stat.id, pedido.id)" v-if="pedido.status_id != 5 && pedido.status_id != 6"></button>
                                                <button class="btn dropdown-btn fw-medium text-capitalize" @click="UpdateOrderStatus(stat.id, pedido.id)" v-else-if="stat.id != 5">{{ stat.stat_desc }}</button>
                                            </li>
                                        </div>
                                    </ul>
                                </div>
                                <button class="btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop" @click.prevent="getOrderItem(pedido.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                                <router-link :to="{ name: 'Bill', params: {id:pedido.id}}" class="nav-link p-0 px-2 text-black" @click="imprimir">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6
                                        18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6"
                                         y="14" width="12" height="8"></rect>
                                    </svg>
                                </router-link>
                                <button v-if="pedido.status_id != 5 && pedido.status_id != 6" @click="StorParams(pedido.id, a = 0)" data-bs-toggle="modal" data-bs-target="#OrderStat" class="btn" data-bs-toggl="tooltip" data-bs-placement="top" title="Change payment method">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3">
                                        <path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                    </svg>
                                </button>
                                <div v-if="pedido.status_id == 5 || pedido.status_id == 6">
                                    <OrderTransfertComponent @get-TransfertItems="TransferItem(pedido.id)" :transfert-items="titems" :tables="tables"/>
                                </div>
                                <button @click="setOrderID(pedido.id)" data-bs-toggle="modal" data-bs-target="#cancelorder" class="btn border-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="#d9534f" stroke-width="1.3" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2">
                                    </rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line>
                                    </svg>
                                </button>
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
                                <form @submit.prevent="CancelBill">
                                    <input class="form-control rounded-0 border-secondary" type="password" v-model="cancel.password" placeholder="password here...">
                                    <input class="form-control rounded-0 border-secondary mt-2" type="text" v-model="cancel.quantidade" placeholder="Quantidade">
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
        InputText
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
                orderID: null
            },
            cancelOrderID: null,
            item_id: null,
            item_pedido: null,
            orderStat: {
                password: null,
                status_id: null
            },
            titems: null,
            billTotal: 0,
            billTotalItem: null

        }
    },
    watch:{
        order: _.debounce(function(newOrder){
            this.getOrder();
            this.getTable()
        }, 8000)
    },

    async created() {
       await this.getOrder()
       this.getTable()
       //this.getCanceledStatus()

    },

    methods: {
       async getOrder() {
            const response = await  axios.get('/api/dashboard/order')
            this.order = response.data.order
            this.status = response.data.status
        },

        getOrderItem(id) {
            this.billTotal = 0
            this.billTotalItem = 0
            axios.get('/api/dashboard/item/' + id).then((response) => {
                this.itens = response.data

                for (let bill of response.data) {
                    this.billTotal += Number(bill.item_total)
                    this.billTotalItem += Number(bill.item_quantidade)
                }
            }).catch((errors) => {
                console.log(errors)
            })
        },

        UpdateOrderStatus(id, pedido) {

           this.$swal.fire({
               title: "Aviso",
               text: "Do you want really to achieve this action?",
               confirmButtonColor: '#333',
               confirmButtonText: 'Confirm',
               showCancelButton: true
           }).then((result) => {
               if (result.isConfirmed){
                   axios.post(`/api/update/status/${id}/${pedido}`, this.cancel).then((response) => {
                       return this.getOrder()
                   }).catch((errors) => {
                       console.log(errors)
                   })
               }
           })

        },

        getTable() {
            axios.get('/api/dashboard/tables').then((response) => {
                this.tables = response.data.tables
                this.busyTables = response.data.busy_tables
            }).catch((errors) => {
                console.log(errors)
            })
        },

        StorParams(OrderId, item_id){
            localStorage.setItem('OrderId', OrderId);
            localStorage.setItem('item_id', item_id);
            this.item_id = localStorage.getItem('item_id');
            this.item_pedido = localStorage.getItem('OrderId');
            console.log(`${this.item_pedido} and ${this.item_id}`)
        },

        setOrderID(id){
           this.cancel.orderID = id;
        },

        CancelBill(){
            axios.post(`/api/cancel/order-item/${this.item_pedido}/${this.item_id}`, this.cancel).then((response) => {
                this.$toast.success(response.data)
                console.log(response.data)
                this.cancel.password = "";
                this.cancel.quantidade = "";
            }).catch((errors) => {
                console.log(errors)
            })
        },

        CancelOrder(){
           axios.post('/api/cancel-order', this.cancel).then((response) => {
               console.log(response.data)
               this.$toast.success(response.data)
           }).catch((errors) => {
               console.log(errors)
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

        TransferItem(id) {
            axios.get('/api/transfert/items/' + id)
                .then((response) => {
                    this.titems = response.data
                    console.log(response.data)
                })
        },

    },

    mounted(){
        console.log(localStorage.getItem('item_id'))
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
