<template>
    <div class="container-fluid">
        <h5 class="text-center text-capitalize">Analise de custo e fornecedore</h5>
        <div class="row d-flex justify-content-center mb-4 p-2 mt-3">
            <div class="col-md-3" v-for="sup in supplierCostData">
                <Knob v-model="sup.percent" readonly :size="130"/>
                <div class="w-100 d-flex flex-column">
                    <small class="fw-medium">{{ sup.sup_name }}</small>
                    <small>Total: <span class="fw-medium text-danger">{{ sup.totalCost }} R$</span></small>
                    <small>Quantidade: <span class="fw-medium text-primary">{{ sup.quantity }}</span></small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <DataTable :value="costData" scrollable scrollHeight="flex" paginator :rows="10">
                    <Column field="product_id" sortable style="width: 15%" header="Product Code"></Column>
                    <Column field="prod_name" sortable style="width: 25%" header="Nome produto"></Column>
                    <Column field="sup_name" sortable style="width: 25%" header="Fornecedor"></Column>
                    <Column field="quantity" sortable style="width: 10%" header="Quantidade"></Column>
                    <Column field="cost" sortable style="width: 15%" header="Custo"></Column>
                    <Column field="totalCost" sortable style="width: 25%" header="Custo total"></Column>
                    <Column field="period" sortable style="width: 25%" header="period"></Column>
                </DataTable>
            </div>
        </div>
    </div>
</template>
<script>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Calendar from 'primevue/calendar';
import Skeleton from 'primevue/skeleton';
import Knob from 'primevue/knob';
import Chip from 'primevue/chip';
import { randTime } from './../../../../rand';

export default {
    name: 'CostIntelligence',

    components: {
        DataTable,
        Column,
        Calendar,
        Skeleton,
        Knob,
        Chip
    },

    data(){
        return{
            costData: null,
            is_skeleton: false,
            value: 50,
            supplierCostData: null
        }
    },

    methods: {
        loadStockAnalyse(){
            this.is_skeleton = true;
            return new Promise(resolve => {
               setTimeout( async () => {
                const apiResponse = await this.axios.get('/api/cost-analyse');
                this.costData = await apiResponse.data.cost;
                this.supplierCostData = await apiResponse.data.supCost
                this.is_skeleton = false
                resolve(tue)
               }, randTime())
            })
        }
    },
    mounted(){
        this.loadStockAnalyse();
    }

}
</script>
