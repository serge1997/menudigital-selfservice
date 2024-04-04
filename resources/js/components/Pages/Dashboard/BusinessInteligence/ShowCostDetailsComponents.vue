<template>
    <div class="col-md-10">
        <Button @click="visibleCostDetailModal = true, $emit('getCostDetails')" icon="pi pi-eye" text/>
        <Dialog v-model:visible="visibleCostDetailModal" maximizable modal header="Detalhes da compra" :style="{ width: '60rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="col-md-10">
                <div v-if="loadDetailBar" class="col-md-8 p-1 mb-3 m-auto">
                    <div class="w-100 d-flex justify-content-center">
                        <small>Carregando...</small>
                    </div>
                    <ProgressBar mode="indeterminate" style="height: 6px"></ProgressBar>
                </div>
                <div class="w-100 mb-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Requisição Code :  {{ requerCode }} </h6>
                            <h6>Requisição feita por :  {{ requerName }} </h6>
                        </div>
                        <div>
                            <h6>Data da requisição:  {{ requerDate }}</h6>
                            <h6>Data entrega:  {{ deliveryDate }}</h6>
                        </div>
                    </div>
                </div>
                <ol class="list-group list-group-numbered">
                    <li v-for="product in productsCostDetail" class="list-group-item">
                        <Chip :label="product.prod_name" />
                        <div class="d-flex justify-content-between p-1 px-3">
                            <ul>
                                <li>Quantidade: {{ product.quantity }}</li>
                                <li>Fornecedor: {{ product.supp_name }}</li>
                                <li>Custo unitario: <Tag :value="product.unitCost"/><small> R$</small></li>
                                <li class="mt-2">Custo Total: <Tag severity="warning" :value="product.totalCost"/><small> R$</small></li>
                            </ul>
                            <ul>

                            </ul>
                        </div>
                    </li>
                </ol>
            </div>
        </Dialog>
    </div>

</template>
<script>
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Chip from 'primevue/chip';
import Tag from 'primevue/tag';
import ProgressBar from 'primevue/progressbar';

export default {
    name: 'ShowCostDetailsComponents',

    props: [
        'productsCostDetail',
        'requerName',
        'deliveryDate',
        'requerDate',
        'requerCode',
        'loadDetailBar'
    ],

    components: {
        Dialog,
        Button,
        DataTable,
        Column,
        Chip,
        Tag,
        ProgressBar
    },
    data(){
        return {
            visibleCostDetailModal: false,
        }
    }
}
</script>
