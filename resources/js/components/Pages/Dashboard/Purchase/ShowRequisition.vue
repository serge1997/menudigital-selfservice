<template>
    <div class="container">
        <Button @click="visibleShowRequisitionModal= true; getRequisitionItens(id)" icon="pi pi-eye" text/>
        <Dialog v-model:visible="visibleShowRequisitionModal" maximizable modal header="Detalhes da requisição" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-100 mt-3">
                <div class="col-md-6 m-auto d-flex justify-content-center mb-3">
                    <Tag severity="warning" :value="requisitions_observation" />
                </div>
                <DataTable :value="requisitions_itens" selectionMode="single"  paginator :rows="10" tableStyle="min-width: 50rem">
                    <Column field="prod_name" sortable style="width: 25%" :header="`${$t('bicost.dataTable.two')}`"></Column>
                    <Column field="quantity" sortable style="width: 25%" :header="`${$t('purchase.dataTable.show.two')}`"></Column>
                    <Column field="confirm_quantity" sortable style="width: 25%" :header="`${$t('purchase.dataTable.show.three')}`"></Column>
                    <Column header="Status" style="width: 25%">
                        <template class="w-100" #body="{ data }">
                            <Tag style="width: 90px" v-if="data.show_status === requisition_status.waiting" value="Pendente" severity="warning" />
                            <Tag style="width: 90px" v-else-if="data.show_status === requisition_status.rejected" value="Recusado" severity="danger" />
                            <Tag style="width: 90px" v-else severity="success" value="Aprovado"/>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </Dialog>
    </div>
</template>
<script>
import DataTable from "primevue/datatable";
import Tag from "primevue/tag";
import Column from "primevue/column";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
export  default {
    name: 'showRequisition',

    components: {
        DataTable,
        Tag,
        Column,
        Button,
        Dialog
    },
    props: ['id'],
    data(){
        return {
            visibleShowRequisitionModal: false,
            requisitions_itens: null,
            requisitions_observation: null,
            requisition_status:{
                waiting: 1,
                approved: 2,
                rejected: 3,
            }
        }
    },
    methods: {
        async getRequisitionItens(id) {
            const response = await axios.get(`/api/purchase-requisition-show/${id}`);
            this.requisitions_itens = response.data
            for (let obs of response.data){
                if (obs.observation !== null){
                    this.requisitions_observation = obs.observation;
                    return;
                }
            }
        },
    }
}
</script>
<script setup>
</script>
