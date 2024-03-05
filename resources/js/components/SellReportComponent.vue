<template>
     <Dialog v-model:visible="visibleClosingLoadModal" :closable="false" maximizable modal header="" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="col-md-10 d-flex flex-column m-auto">
            <span class="text-center text-warning"><i style="font-size: 2.3em;" class="pi pi-exclamation-triangle"></i></span>
            <p class="fs-5 text-center">Essa Atividade não pode ser cancelada, aguarde até o final.</p>
            <p v-if="visibleClosingLoadModal" class="text-center">{{ loadMessage }}</p>
            <ProgressBar v-if="visibleClosingLoadModal" mode="indeterminate" style="height: 6px"></ProgressBar>
        </div>
    </Dialog>
    <div>
        <button type="button" class="btn fw-medium caixa-btn" data-bs-toggle="modal" data-bs-target="#report">
            Sell Report
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8">
                </polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </button>

        <div class="modal fade" id="report" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content rounded-0 border-0">
                <div class="modal-header">
                    <div class="d-flex flex-column mt-1">
                        <h5 class="modal-title" id="staticBackdropLabel">Report</h5>
                        <div class="text-capitalize">
                            <button @click="ResetStockTable" class="btn sellmodal-btn text-capitalize rounded-0 text-capitalize">Close journey</button>
                            <span class="px-2"></span>
                            <button class="btn sellmodal-btn text-capitalize rounded-0">print</button>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-active">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quandide</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in report">
                                <td>{{ item.item_name }}</td>
                                <td>{{ item.quantidade }}</td>
                                <td>{{ item.total }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr v-for="paiment in paiment_data">
                                <th>{{ paiment.stat_desc }}</th>
                                <th></th>
                                <th>{{ paiment.cash }}</th>
                            </tr>
                            <tr>
                                <th>Canceled</th>
                                <th></th>
                                <th>{{ valorCanceled }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Dialog from 'primevue/dialog';
import ProgressBar from 'primevue/progressbar';
export default {
    name: 'SellReportComponent',

    components: {
        Dialog,
        ProgressBar
    },

    data() {
        return {
            report: null,
            paiment_data: null,
            valorCanceled: null,
            visibleClosingLoadModal: false,
            loadMessage: 'Aguarde...'
        }
    },

    methods: {
        getReport(){
            axios.get('/api/order-itens-report').then((response) => {
                this.report = response.data.report
                this.paiment_data = response.data.paiment
                for (let e of response.data.valcanceled){
                    this.valorCanceled = e.valor
                }
            }).catch((errors) =>{
                console.log(errors)
            })
        },

        ResetStockTable(){

            this.$swal.fire({
                text: 'está sendo fechando a caixa e reniçializando o estoque. clique em ok para continuar',
                icon: 'warning',
                confirmButtonColor: '#e63958',
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed){
                    this.visibleClosingLoadModal = true;
                    setTimeout(() => {this.loadMessage = "Verificando..."}, 1000);
                    setTimeout(() => {this.loadMessage = "Atualizando o inventario..."}, 4000);
                    setTimeout(() => {this.loadMessage = "Greando as requisições..."}, 9000);
                    setTimeout(() => {this.loadMessage = "Feachando as vendas do dia..."}, 20000);
                    setTimeout(() => {this.loadMessage = "Enviando e-mail..."}, 20000);
                    return new Promise(resolve => {
                        setTimeout(() => {
                            axios.put('/api/reset-saldo')
                            .then((response) => {
                                this.$swal.fire({
                                    text: response.data,
                                    icon: 'success'
                                });
                            })
                            .catch((errors) => {
                                this.$swal.fire({
                                    text: errors.response.data,
                                    icon: 'success'
                                });
                            })
                            .finally(() => {
                                this.visibleClosingLoadModal = false;
                            })
                        }, 50000)
                    })
                }
            })

        }
    },

    mounted(){
        this.getReport()
    }
}
</script>

<style scoped>
.sellmodal-btn {
    border-bottom: 1px solid #e63958;
    transition: all .3s ease;
}

.sellmodal-btn:hover {
    background-color: #e63958;
    color: #fff;
}
</style>
