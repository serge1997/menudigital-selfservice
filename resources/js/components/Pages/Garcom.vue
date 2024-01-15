<template>
    <div class="col-12">
        <div class="p-1 col-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>
            </svg>
            {{ username }}
        </div>
        <div class="d-flex justify-content-between p-0">
            <div class="col-8 m-auto">
                <h4 class="text-capitalize">Espaço Garcom</h4>
                <div class="w-100 d-flex justify-content-center p-2">
                    <router-link class="col-2 d-flex flex-column nav-link" :to="{ name: 'Menu' }">
                        <img class="w-25 m-auto" src="../../../../public/img/iconmenu.png" alt="">
                        <span class="text-center fw-medium text-capitalize">Menu</span>
                    </router-link>
                    <router-link class="col-2 d-flex flex-column nav-link" :to="{ name: 'Home' }">
                        <img class="w-25 m-auto" src="../../../../public/img/table.png" alt="">
                        <span class="text-center fw-medium text-capitalize">New order</span>
                    </router-link>
                </div>
                <div class="py-4 col-lg-10 col-md-12">
                    <h5 class="text-center fw-normal text-capitalize">Ocupação mesa na sala</h5>
                    <div class="col-lg-10 col-md-12 m-auto d-flex justify-content-center flex-wrap p-2 mt-2">
                        <button  v-for="tab in tables" class="btn col-lg-4 col-md-5">
                            <div class="bg-success border p-2">
                                <h6 class="text-white text-center fw-normal">Mesa {{ tab.table }}<br><small>livre</small></h6>
                            </div>
                        </button>
                       <button @click="visibleStockAddModal = true" data-bs-toggle="modal" v-for="busy in busyTables" class="col-lg-4 col-md-5 btn" @click.prevent="getOrderItem(busy.id)">
                            <div class="bg-danger border p-0">
                                <h6 class="text-white text-center fw-normal">Mesa {{ busy.ped_tableNumber }}<br><small>ocupada</small><br><small>{{ busy.name }}</small></h6>
                            </div>
                       </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="AddMenu" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <menu-component :id="orderID"></menu-component>
                    </div>
                    <div class="modal-footer">
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <Dialog v-model:visible="visibleStockAddModal" maximizable modal header="Table order" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-100 d-flex flex-column">
                <div class="w-100">
                    <Button @click="visibleStockAddModal = false; visibleMenuModal = true" data-bs-toggle="modal" data-bs-target="#AddMenu">
                        Adicionar
                    </Button>
                </div>
                <div class="w-100">
                    <DataTable :value="itens">
                        <Column field="item_name" sortable style="width: 25%" exportHeader="Product Code" header="Item"></Column>
                        <Column field="item_quantidade" sortable style="width: 25%" header="Quantity"></Column>
                        <Column field="item_price" sortable style="width: 25%" header="Cost"></Column>
                        <Column field="item_total" sortable style="width: 25%" header="Subtotal"></Column>
                    </DataTable>
                </div>
                <div class="w-100 p-2">
                   <h6 class="fs-5">Total : {{billTotal.toFixed(2)}} R$</h6>
                    <h6 class="fs-5">Total Itens: {{ billTotalItem }}</h6>
                </div>
            </div>
        </Dialog>
    </div>
</template>

<script>
import _ from 'lodash'
import MenuComponent from './MenuComponent.vue';
import authuser from "./auth.js";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
export default {
    name: 'OperadorPanel',

    components: {
        MenuComponent,
        Dialog,
        Button,
        DataTable,
        Column
    },

    data() {
        return {
            order: null,
            itens: null,
            status: null,
            linkSTyle: 'text-white',
            tables: null,
            busyTables: null,
            orderID: null,
            //orderID: localStorage.getItem('orderID'),
            addnewItem: null,
            billTotal: 0,
            billTotalItem: null,
            username: null,
            visibleStockAddModal:false,
            visibleMenuModal: false
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
            this.billTotal = 0
            this.billTotalItem = 0
            axios.get('/api/dashboard/item/' + id).then((response) => {
                this.itens = response.data
                this.orderID = id
                for (let bill of response.data) {
                    this.billTotal += Number(bill.item_total)
                    this.billTotalItem += Number(bill.item_quantidade)
                }
                console.log(localStorage.getItem('orderID'))
            }).catch((errors) => {
                console.log(errors)
            })
        },

        UpdateOrderStatus(id, pedido) {
            axios.post(`/api/update/status/${id}/${pedido}`).then((response) => {
                return this.getOrder()
            }).catch((errors) => {
                console.log(errors)
            })
        },

        getTable() {
            axios.get('/api/dashboard/tables').then((response) => {
                this.tables = response.data.tables
                this.busyTables = response.data.busy_tables
                console.log(response.data)
            }).catch((errors) => {
                console.log(errors)
            })
        }

    },

    mounted(){
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
        authuser.then(result => {this.username = result.name})
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
