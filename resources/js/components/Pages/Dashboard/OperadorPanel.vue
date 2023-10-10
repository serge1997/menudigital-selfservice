<template>
    <div class="col-12">
         <div class="position-fixed">
            <SideBarComponent></SideBarComponent>
        </div>
        <div class="d-flex justify-content-between p-0">
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
                <div class="py-2">
                    <button class="btn">Todos</button>
                    <button class="btn">Pagou</button>
                </div>
               <table class="table">
                    <thead>
                        <tr>
                            <th class="bg-primary text-white">Id</th>
                            <th class="bg-primary text-white">Nome</th>
                            <th class="bg-primary text-white">Mesa</th>
                            <th class="bg-primary text-white">Valor Total</th>
                            <th class="bg-primary text-white">Status</th>
                            <th class="bg-primary text-white">Ação</th>
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
                                    <ul class="dropdown-menu p-0">
                                        <h6 class="text-capitalize fw-medium">pagamento</h6>
                                        <div class="">
                                            <li v-for="stat in status">
                                                <button class="btn" @click="UpdateOrderStatus(stat.id, pedido.id)" v-if="stat.id != 5">{{ stat.stat_desc }}</button>
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
                                <router-link :to="{ name: 'Bill', params: {id:pedido.id}}" class="nav-link p-0 px-2 text-black">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" 
                                        stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" 
                                        class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 
                                        18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6"
                                         y="14" width="12" height="8"></rect>
                                    </svg>
                                </router-link>
                            </td>
                        </tr>
                    </tbody>
               </table>
            </div>
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content rounded-0">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table w-100">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>quantidade</th>
                                        <th>Valor</th>
                                        <th>Total</th>
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
                                            <button data-bs-toggle="modal" @click="StorParams(item.id, item.item_id)" data-bs-target="#cancel" class="btn">Cancelar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="cancel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                                    <form @submit.prevent="CancelBill">
                                        <input class="form-control rounded-0 border-secondary" type="password" v-model="cancel.password" placeholder="password here...">
                                        <input class="btn rounded-0 border mt-4 w-50 bg-warning" type="submit" value="Ok">
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
import SideBarComponent from './SideBarComponent.vue'
import _ from 'lodash'
export default {
    name: 'OperadorPanel',

    components: {
        SideBarComponent
    },

    data() {
        return {
            order: null,
            itens: null,
            status: null,
            linkSTyle: 'text-white',
            tables: null,
            busyTables: null,
            cancel: {
                password: null,
            },
            item_id: localStorage.getItem('item_id'),
            item_pedido: localStorage.getItem('OrderId')
            
        }
    },
    watch:{
        order: _.debounce(function(newOrder){
            this.getOrder();
            this.getTable()
        }, 10000)
    },

    async created() {
       await this.getOrder()
       this.getTable()
       this.getCanceledStatus()

    },

    methods: {
       async getOrder() {
            const response = await  axios.get('/api/dashboard/order')
            this.order = response.data.order
            this.status = response.data.status

            /*for(const a of this.order){
                console.log(`valor of a is ${a.id}`)
            }*/
        },

        getOrderItem(id) {
            axios.get('/api/dashboard/item/' + id).then((response) => {
                this.itens = response.data
               
            }).catch((errors) => {
                console.log(errors)
            })
        },

        UpdateOrderStatus(id, pedido) {
            axios.post(`/api/update/status/${id}/${pedido}`, this.cancel).then((response) => {
                return this.getOrder()
            }).catch((errors) => {
                console.log(errors)
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
            localStorage.setItem('OrderId', OrderId)
            localStorage.setItem('item_id', item_id)
            console.log(item_id)
        },

        CancelBill(){
            axios.post(`/api/dashboard/cancela/${this.item_pedido}/${this.item_id}`, this.cancel).then((response) => {
                //localStorage.removeItem('OrderId')
                //localStorage.removeItem('item_id')
                console.log(response.data)
            })
        }

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