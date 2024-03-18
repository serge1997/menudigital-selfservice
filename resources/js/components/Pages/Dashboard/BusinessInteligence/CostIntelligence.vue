<template>
    <div class="container-fluid">
        <h5 class="text-center text-capitalize">Analise de custo e fornecedore</h5>
        <div class="row d-flex flex-column">
            <div class="col-md-8 m-auto mt-3 p-2">
                <ProgressBar v-if="load" mode="indeterminate" style="height: 6px"></ProgressBar>
            </div>
            <Toolbar>
                <template #center>
                    <div style="width: 100%;" class="d-flex justify-content-between">
                        <div class="col-md-12 d-flex justify-content-end gap-3">
                            <div class="d-flex flex-column gap-2 col-md-5">
                                <label>
                                    <span><i style="font-size: 14px;" @click.prevent="limparFiltroData" class="pi pi-filter-slash"></i></span>
                                    Produto | Fornecedor
                                </label>
                                <span class="p-input-icon-left">
                                    <InputText @change="getFiltersData" v-model="filtreParam.prodName" class="w-100" placeholder="produto, fornecedor" @input="filterDataTable" />
                                </span>
                            </div>
                            <div class="d-flex flex-column gap-2 col-md-4">
                                <label>
                                    <span><i style="font-size: 14px;" @click.prevent="limparFiltroData" class="pi pi-filter-slash"></i></span>
                                    Ano 
                                </label>
                                <Dropdown @change="getFiltersData" class="w-100" :options="years" optionValue="year" optionLabel="year" placeholder="Selecione ano..." v-model="filtreParam.year" />
                            </div>
                            <div class="d-flex flex-column gap-2 col-md-4">
                                <label>
                                    <span><i style="font-size: 14px;" @click.prevent="limparFiltroData" class="pi pi-filter-slash"></i></span>
                                    Mês 
                                </label>
                                <Dropdown @change="getFiltersData" class="w-100" :options="monthData" optionValue="value" optionLabel="month" placeholder="Selecione mês..." v-model="filtreParam.month" />
                            </div>
                        </div>
                    </div>
                </template>
            </Toolbar>
        </div>
        <div class="row d-flex justify-content-between mb-4 p-2 mt-3 gap-3">
            <div class="col-lg-6 col-md-10 shadow">
                <div class="w-100" id="suppChart"></div>
            </div>
            <div class="col-lg-5 d-flex flex-wrap col-md-10 shadow">
                <div class="col-md-3 d-flex flex-column align-items-center" v-for="sup in supplierCostData">
                    <div>
                        <Knob class="m-auto" :valueColor=" sup.percent > 50 ? '#dc2626' : '#94a3b8'" v-model="sup.percent" readonly :size="100"/>
                    </div>
                    <div class="w-100 d-flex flex-column">
                        <small class="fw-medium">{{ sup.sup_name }}</small>
                        <small>Quantidade: <span class="fw-medium text-primary">{{ sup.quantity }}</span></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <DataTable :value="costData" scrollable scrollHeight="flex" paginator :rows="10">
                    <template #header>
                        <div style="text-align: left">
                            <Button icon="pi pi-external-link" label="Export" @click="downloadCsv" />
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
import { exportCSV } from './../csv.js';
import Toolbar from 'primevue/toolbar';

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
        ShowCostDetailsComponents,
        Toolbar
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
                {year: "2030"}
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
                this.mountSuppChart()
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
                        this.mountSuppChart()
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
        },
        downloadCsv(){
            exportCSV(this.costData)
        },
        mountSuppChart(){

            if (this.supplierCostData !== null){
                var supplier = [];
                var sell = [];
                for (let obj of this.supplierCostData){
                    supplier.push(obj.sup_name);
                    sell.push(Number(obj.totalCost))
                }
                const data = [{
                    x: sell,
                    y: supplier,
                    width: 0.6,
                    type: "bar",
                    orientation:"h",
                    marker: {color:"#9ca3af"}
                }];
                const layout = {title: "Custo por fornecedore"};
                Plotly.newPlot('suppChart', data, layout);
            }
        }
    },

    mounted(){
        this.loadStockAnalyse();
    }

}
</script>
