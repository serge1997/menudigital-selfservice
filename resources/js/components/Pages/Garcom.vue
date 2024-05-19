<template>
    <div class="row">
        <template id="swal-template">
            <swal-html>
                Loading
            </swal-html>
        </template>
        <div class="p-1 col-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>
            </svg>
            {{ username }}
        </div>
        <div class="d-flex justify-content-between p-0">
            <div class="col-8 d-flex flex-column align-items-center m-auto">
                <h4 class="text-capitalize">{{ $t('waiterpage.title') }}</h4>
                <div class="col-md-12 d-flex justify-content-center p-2">
                    <router-link class="col-md-2 d-flex flex-column nav-link" :to="{ name: 'Menu' }">
                        <img class="w-25 m-auto" src="../../../../public/img/iconmenu.png" alt="">
                        <span class="text-center fw-medium text-capitalize">Menu</span>
                    </router-link>
                    <router-link class="col-md-2 d-flex flex-column nav-link" :to="{ name: 'Home' }">
                        <img class="w-25 m-auto" src="../../../../public/img/table.png" alt="">
                        <span class="text-center fw-medium text-capitalize">{{ $t('waiterpage.icons.two') }}</span>
                    </router-link>
                    <router-link class="col-md-2 d-flex flex-column nav-link" :to="{ name: 'Reservation' }">
                        <img class="w-25 m-auto" src="../../../../public/img/table.png" alt="">
                        <span class="text-center fw-medium text-capitalize">{{ $t('waiterpage.icons.three') }}</span>
                    </router-link>
                </div>
                <div class="py-4 col-lg-10 col-md-12 d-flex flex-column justify-content-center">
                    <h5 class="text-center fw-normal text-capitalize">{{ $t('operator.table_box_title') }}</h5>
                    <div class="col-lg-10 col-md-12 m-auto d-flex justify-content-center flex-wrap p-2 mt-2">
                        <button  v-for="tab in tables" class="col-lg-4 col-md-5 btn border-0 p-0">
                            <div class="col-md-10 d-flex flex-column border">
                                <img class="col-md-5 img-thumbnail border-0" src="/img/free.png" />
                                <Tag severity="success" :value ="`${ $t('operator.table')} ${tab.table}`"/>
                            </div>
                        </button>
                       <button @click="visibleStockAddModal = true" data-bs-toggle="modal" v-for="busy in busyTables" class="col-lg-4 col-md-5 btn border-0 p-0" @click.prevent="getOrderItem(busy.id)">
                             <div class="col-md-10 d-flex flex-column border mt-2">
                                <div class="col-md-10 d-flex justify-content-between align-items-center">
                                    <img class="col-md-5 img-thumbnail border-0" src="/img/busy.png" />
                                    <Badge severity="warning" :value="busy.customer " />
                                </div>
                                <small class="text-left">{{busy.name}}</small>
                                <Tag severity="danger" :value ="`${ $t('operator.table')} ${busy.ped_tableNumber} (${busy.timing})`"/>
                            </div>
                       </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="AddMenu" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
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
        <Dialog v-model:visible="visibleStockAddModal" maximizable modal :header="`${ $t('waiterpage.modal.title')} `" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-100 d-flex flex-column">
                <div class="w-100">
                    <Button @click="visibleStockAddModal = false; visibleMenuModal = true" data-bs-toggle="modal" data-bs-target="#AddMenu">
                        {{$t('waiterpage.modal.btns.one')}}
                    </Button>
                </div>
                <div class="w-100">
                    <DataTable :value="itens">
                        <Column field="item_name" sortable style="width: 25%" header="Item"></Column>
                        <Column field="item_quantidade" sortable style="width: 25%" :header="`${ $t('bicost.dataTable.four')} `"></Column>
                        <Column field="item_price" sortable style="width: 25%" :header="`${ $t('waiterpage.dataTable.three')} `"></Column>
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
import { getAuth } from "./auth.js";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Tag from 'primevue/tag';
import Badge from 'primevue/badge';
import Chip from 'primevue/chip';
export default {
    name: 'OperadorPanel',

    components: {
        MenuComponent,
        Dialog,
        Button,
        DataTable,
        Column,
        Tag,
        Badge,
        Chip
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
        busyTables: _.debounce(function(newOrder){
            this.getTable()
        }, 20000)
    },

    async created() {
       await this.getOrder()
       this.getTable()

    },

    methods: {
       async getOrder() {
            const response = await  axios.get('/api/dashboard/order')
            this.order = await response.data.order
            this.status = await response.data.status
        },

        async getOrderItem(id) {
            this.billTotal = 0
            this.billTotalItem = 0
            let response = await axios.get('/api/order-menu-itens/' + id)
            this.itens = await response.data
            this.orderID = id
            for (let bill of this.itens) {
                this.billTotal += Number(bill.item_total)
                this.billTotalItem += Number(bill.item_quantidade)
            }
        },

        UpdateOrderStatus(id, pedido) {
            axios.post(`/api/update/status/${id}/${pedido}`).then((response) => {
                return this.getOrder()
            }).catch((errors) => {
                console.log(errors)
            })
        },

        async getTable() {
            let response = await axios.get('/api/tablenumbers-orders')
            this.tables = await response.data.tables
            this.busyTables = await response.data.busyTables
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
            }, 4000)
        }

    },

    mounted(){
        this.mountedLoadEffect();
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
        getAuth().then(result => {
            this.username = result.name
        });
    }
}

</script>
<style scoped>
</style>
