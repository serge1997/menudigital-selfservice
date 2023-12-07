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
                                                        <button @click="$emit('UpdateOrderStatus', stat.id, bill.id)" class="btn dropdown-btn fw-medium text-capitalize" >{{ stat.stat_desc }}</button>
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
    </div>
</template>

<script>

export default {
    name: 'BillHistoryComponent',

    props: ['status'],

    data() {
        return {
            gerente: null,
            isgerente: 1,
            bills: null
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
