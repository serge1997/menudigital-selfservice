<template>
    <div class="container-fluid">
        <h5 class="text-center text-capitalize">Analise de custo e fornecedore</h5>
        <div class="row d-flex justify-content-center mb-4 p-2 mt-3 gap-3">
            <div class="col-md-3 d-flex align-items-center justify-content-flex gap-3 border rounded-2 p-2 shadow-sm" v-for="sup in supplierCostData">
                <Knob class="m-auto" v-model="sup.percent" readonly :size="130"/>
                <div class="w-100 d-flex flex-column">
                    <small class="fw-medium">{{ sup.sup_name }}</small>
                    <small>Total: <span class="fw-medium text-danger">{{ sup.totalCost }} R$</span></small>
                    <small>Quantidade: <span class="fw-medium text-primary">{{ sup.quantity }}</span></small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 m-auto mt-3 p-2">
                <ProgressBar v-if="load" mode="indeterminate" style="height: 6px"></ProgressBar>
            </div>
            <div class="col-md-12">
                <DataTable :value="costData" scrollable scrollHeight="flex" paginator :rows="10">
                    <template #header>
                        <div class="d-flex justify-content-between">
                            <div style="text-align: left">
                                <Button icon="pi pi-external-link" label="Export" @click="exportCSV($event)" />
                            </div>
                            <div class="col-md-10 d-flex justify-content-end gap-3">
                                <div class="d-flex flex-column gap-2 col-md-3">
                                    <label>Produto | Fornecedor</label>
                                    <span class="p-input-icon-left">
                                        <i class="pi pi-search" />
                                        <InputText @change="getFiltersData" class="w-100" v-model="filtreParam.prodName" placeholder="produto, fornecedor" @input="filterDataTable" />
                                    </span>
                                </div>
                                <div class="d-flex flex-column gap-2 col-md-3">
                                    <label>Ano </label>
                                    <Dropdown @change="getFiltersData" class="w-100" :options="years" optionValue="year" optionLabel="year" placeholder="Selecione ano..." v-model="filtreParam.year" />
                                </div>
                                <div class="d-flex flex-column gap-2 col-md-3">
                                    <label>Mês </label>
                                    <Dropdown @change="getFiltersData" class="w-100" :options="monthData" optionValue="value" optionLabel="month" placeholder="Selecione mês..." v-model="filtreParam.month" />
                                </div>
                            </div>
                        </div>
                    </template>
                    <Column field="product_id" sortable style="width: 15%" header="Product Code"></Column>
                    <Column field="prod_name" sortable style="width: 25%" header="Nome produto"></Column>
                    <Column field="sup_name" sortable style="width: 25%" header="Fornecedor"></Column>
                    <Column field="quantity" sortable style="width: 10%" header="Quantidade"></Column>
                    <Column field="cost" sortable style="width: 15%" header="Custo"></Column>
                    <Column field="totalCost" sortable style="width: 25%" header="Custo total"></Column>
                    <Column field="period" sortable style="width: 25%" header="period"></Column>
                    <Column field="prod_unmed" header="Ação" style="width: 25%">
                        <template #body="{ data }">
                            <div class="d-flex">
                                <ShowCostDetailsComponents @get-cost-details="getCostDetails(data.requisition_id) "
                                    :products-cost-detail="costshow"
                                    :requer-name="requerName"
                                    :requer-code="requerCode"
                                    :delivery-date="deliveryDate"
                                    :requer-date="requerDate"
                                    :load-detail-bar="loadDetailBar"
                                    />
                            </div>
                        </template>
                    </Column>
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
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import ProgressBar from 'primevue/progressbar';
import ShowCostDetailsComponents from './ShowCostDetailsComponents.vue';
import { randTime } from './../../../../rand';

export default {
    name: 'CostIntelligence',

    components: {
        DataTable,
        Column,
        Calendar,
        Skeleton,
        Knob,
        Chip,
        Button,
        InputText,
        Dropdown,
        ProgressBar,
        ShowCostDetailsComponents
    },

    data(){
        return{
            costData: null,
            is_skeleton: false,
            value: 50,
            supplierCostData: null,
            load: false,
            filtreParam: {
                prodName: null,
                month: null,
                year: null
            },
            monthData: [
                {value: "Jan", month: "Janeiro"},
                {value: "Feb", month: "Fevrereiro"},
                {value: "Mar", month: "Março"},
                {value: "Apr", month: "Abril"},
                {value: "Mai", month: "Maio"},
                {value: "Jun", month: "Juno"},
                {value: "Jul", month: "Julho"},
                {value: "Aug", month: "Agosto"},
                {value: "Set", month: "Setembro"},
                {value: "Oct", month: "Octubro"},
                {value: "Nov", month: "Novembro"},
                {value: "Dec", month: "Dezembro"}
            ],
            years: [
                {year: "2024"},
                {year: "2025"},
                {year: "2026"},
                {year: "2027"},
                {year: "2028"},
                {year: "2029"},
            ],
            costshow: null,
            requerName: null,
            deliveryDate: null,
            requerDate: null,
            requerCode: null,
            loadDetailBar: false,
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
                resolve(true)
               }, randTime())
            })
        },
        getFiltersData(){
            this.load = true;
            return new Promise(resole => {
                setTimeout(() => {
                    axios.post('/api/cost-analyse-filter', this.filtreParam).then( async (filterResponse) => {
                        this.costData = await filterResponse.data.cost;
                        this.supplierCostData = await filterResponse.data.supCost
                        resole(true);
                    })
                    .catch(errors => console.log(errors))
                    .finally(this.load = false);
                }, randTime())
            });
        },
        getCostDetails(requisition_id){
            this.loadDetailBar = true
            this.costshow = null
            return new Promise((resole) => {
               setTimeout( async () => {
                const apiResponse = await axios.get('/api/stock-requistion/' + requisition_id)
                this.costshow = await apiResponse.data
                for (let dt of this.costshow){
                    this.requerName = dt.user_name;
                    this.requerCode = dt.requisition_code;
                    this.requerDate = dt.requisition_date;
                    this.deliveryDate = dt.emissao
                    this.loadDetailBar = false
                }
               }, randTime())

            })
        }
    },

    mounted(){
        this.loadStockAnalyse();
    }

}
</script>
