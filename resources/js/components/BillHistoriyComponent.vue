<template>
    <div v-if="gerente === isgerente">
        <button type="button" class="btn fw-medium caixa-btn" data-bs-toggle="modal" data-bs-target="#BillHistory">
            Bill History
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8">
            </polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </button>

        <div class="modal fade" id="BillHistory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content rounded-0 border-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Bill history</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-active">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Customer</th>
                                    <th>Table number</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>emissao</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="bill in bills">
                                    <td>{{ bill.id }}</td>
                                    <td>{{ bill.ped_customerName }}</td>
                                    <td>{{ bill.ped_tableNumber }}</td>
                                    <td> {{ bill.total }}</td>
                                    <td>{{ bill.stat_desc }}</td>
                                    <td>{{ bill.ped_emissao }}</td>
                                    <td class="d-flex jusitify-content-center align-items-center">
                                        <div class="btn-group">
                                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            </button>
                                            <ul class="dropdown-menu p-0 shadow">
                                                <h6 class="text-capitalize fw-medium p-2 bg-dark text-white">pagamento</h6>
                                                <div class="">
                                                    <li v-for="stat in status">
                                                        <button @click="storeParam(bill.id)" class="btn dropdown-btn fw-medium text-capitalize" data-bs-toggle="modal" data-bs-target="#OrderStatHistory">{{ stat.stat_desc }}</button>
                                                    </li>
                                                </div>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="OrderStatHistory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                        <p class="fs-5">Manager Password needed history</p>
                        <div>
                            <form @submit.prevent="EditOrderStatHistory">
                                <input class="form-control rounded-0 border-secondary" type="password" v-model="orderStat.password" placeholder="password here...">
                                <select class="form-select mt-3 border-secondary rounded-0" v-model="orderStat.status_id">
                                    <option v-for="stat in status" :value="stat.id">
                                        {{ stat.stat_desc }}
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
</template>

<script>

import axios from "axios";

export default {
    name: 'BillHistoryComponent',

    props: ['status'],

    data() {
        return {
            gerente: null,
            isgerente: 1,
            bills: null,
            order_id: null,
            orderStat: {
                password: null,
                status_id: null
            },

        }
    },

    methods: {
        getBillHistory(){
            axios.get('/api/bill-history').then((response) => {
                console.log(response.data)
                this.bills = response.data
            }).catch((errors) => {
                console.log(errors)
            })
        },

        EditOrderStatHistory(){
            axios.post(`/api/editorder/stat/${this.order_id}`, this.orderStat)
                .then((response) => {
                    this.$toast.success(response.data)
                    this.orderStat.password = "";
                }).catch((errors) => {
                    this.$toast.error(errors.response.data)
                    console.log(errors.response.data.errors.password)
            })
        },
        storeParam(id){
            localStorage.removeItem('orderID');
            localStorage.setItem('orderHistory', id);
            this.order_id = localStorage.getItem('orderHistory');
        }
    },

    mounted(){
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
        axios.get('/api/user').then((response) => {
            this.gerente = response.data.group_id
        });

        this.getBillHistory();


    }
}
</script>
<style scoped>
.modal-body {
    height: 400px;
}
</style>
