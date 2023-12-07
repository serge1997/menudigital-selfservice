<template>
    <div class="col-12">
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
                <div class="py-4">
                    <h5 class="text-center fw-normal text-capitalize">Ocupação mesa na sala</h5>
                    <div class="d-flex justify-content-center flex-wrap p-2 mt-2">
                        <button  v-for="tab in tables" class="btn col-2">
                            <div class="bg-success border p-2">
                                <h6 class="text-white text-center fw-normal">Mesa {{ tab.table }}<br><small>livre</small></h6>
                            </div>
                        </button>
                       <button data-bs-toggle="modal" data-bs-target="#staticBackdrop" v-for="busy in busyTables" class="col-2 btn" @click.prevent="getOrderItem(busy.id)">
                            <div class="bg-danger border p-0">
                                <h6 class="text-white text-center fw-normal">Mesa {{ busy.ped_tableNumber }}<br><small>ocupada</small><br><small>{{ busy.name }}</small></h6>
                            </div>
                       </button>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <button class="btn rounded-0 bg-dark text-white capitalize" data-bs-toggle="modal" data-bs-target="#AddMenu">
                            Adicionar
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-active">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantidade</th>
                                    <th>Valor</th>
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
                                <tr class="fw-medium fs-5">
                                    <td>Totais</td>
                                    <td>{{ billTotalItem }}</td>
                                    <td class="bg-dark"></td>
                                    <td class="bg-dark text-white"><span>{{ billTotal }} R$</span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                    </div>
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
    </div>
</template>

<script>
import _ from 'lodash'
import MenuComponent from './MenuComponent.vue';
export default {
    name: 'OperadorPanel',

    components: {
        MenuComponent
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
            billTotalItem: null
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
        console.log(this.orderID)
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
